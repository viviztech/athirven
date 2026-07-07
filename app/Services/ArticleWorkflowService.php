<?php

namespace App\Services;

use App\Enums\ArticleStatus;
use App\Exceptions\InvalidArticleTransitionException;
use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Enforces the editorial state machine documented in docs/architecture.md.
 * Article::status must never be written directly from a form — every
 * transition goes through here so illegal jumps and permission checks
 * can't be bypassed by a stray field edit.
 */
class ArticleWorkflowService
{
    /**
     * @var array<string, array<string, string>> from-status => [to-status => required permission]
     */
    private const TRANSITIONS = [
        'draft' => ['submitted' => 'articles.submit'],
        'submitted' => ['in_review' => 'articles.review'],
        'in_review' => [
            'approved' => 'articles.approve',
            'needs_revision' => 'articles.reject',
        ],
        'needs_revision' => ['submitted' => 'articles.submit'],
        'approved' => ['scheduled' => 'articles.schedule'],
        'scheduled' => ['published' => 'articles.publish'],
        'published' => [
            'needs_revision' => 'articles.reject',
            'archived' => 'articles.archive',
        ],
    ];

    /**
     * @return array<int, ArticleStatus> statuses this user may currently move the article to
     */
    public function availableTransitions(Article $article, User $user): array
    {
        $map = self::TRANSITIONS[$article->status->value] ?? [];

        return collect($map)
            ->filter(fn (string $permission) => $user->can($permission))
            ->filter(fn (string $permission, string $to) => $this->passesOwnershipGuard($article, $user, $to))
            ->keys()
            ->map(fn (string $value) => ArticleStatus::from($value))
            ->all();
    }

    /**
     * @param  array{revision_notes?: string, issue_id?: int, scheduled_at?: \DateTimeInterface}  $meta
     */
    public function transition(Article $article, ArticleStatus $to, User $user, array $meta = []): Article
    {
        $from = $article->status;
        $map = self::TRANSITIONS[$from->value] ?? [];

        if (! array_key_exists($to->value, $map)) {
            throw InvalidArticleTransitionException::forTransition($from, $to);
        }

        $permission = $map[$to->value];

        if (! $user->can($permission)) {
            throw new AuthorizationException("Missing permission [{$permission}] to move an article from [{$from->value}] to [{$to->value}].");
        }

        if (! $this->passesOwnershipGuard($article, $user, $to->value)) {
            throw new AuthorizationException('You may only submit your own articles.');
        }

        match ($to) {
            ArticleStatus::Submitted => $article->submitted_at = now(),
            ArticleStatus::NeedsRevision => $article->revision_notes = $meta['revision_notes']
                ?? throw new \InvalidArgumentException('revision_notes is required when sending an article back for revision.'),
            ArticleStatus::Scheduled => $this->applyScheduling($article, $meta),
            ArticleStatus::Published => $article->published_at ??= now(),
            default => null,
        };

        $article->status = $to;
        $article->save();

        return $article;
    }

    public function markProofread(Article $article, User $user, ?string $notes = null): Article
    {
        if (! $user->can('articles.review')) {
            throw new AuthorizationException('Missing permission [articles.review] to record proofreading.');
        }

        $article->update([
            'proofread_at' => now(),
            'proofread_by_id' => $user->id,
            'proofreader_notes' => $notes,
        ]);

        return $article;
    }

    private function passesOwnershipGuard(Article $article, User $user, string $to): bool
    {
        if ($to !== ArticleStatus::Submitted->value) {
            return true;
        }

        if ($user->can('articles.edit.any')) {
            return true;
        }

        return $article->created_by_id === $user->id;
    }

    /**
     * @param  array{issue_id?: int, scheduled_at?: \DateTimeInterface}  $meta
     */
    private function applyScheduling(Article $article, array $meta): void
    {
        if (isset($meta['issue_id'])) {
            $article->issue_id = $meta['issue_id'];
        }

        if (! $article->issue_id) {
            throw new \InvalidArgumentException('An article must be assigned to an issue before it can be scheduled.');
        }

        $article->scheduled_at = $meta['scheduled_at'] ?? $article->scheduled_at ?? now();
    }
}
