<?php

use App\Enums\CommentStatus;
use App\Models\Article;
use App\Models\Comment;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Article $article;

    public string $authorName = '';

    public string $body = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'authorName' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    #[Computed]
    public function approvedComments()
    {
        return $this->article->comments()
            ->where('status', CommentStatus::Approved)
            ->oldest()
            ->get();
    }

    public function submit(): void
    {
        $this->validate();

        // Pre-moderation is the default for political categories (see docs/architecture.md);
        // post-moderated articles publish the comment immediately instead.
        $status = $this->article->comment_moderation_mode === 'post'
            ? CommentStatus::Approved
            : CommentStatus::Pending;

        Comment::create([
            'article_id' => $this->article->id,
            'author_display_name' => $this->authorName,
            'body' => $this->body,
            'status' => $status,
            'ip_hash' => hash('sha256', request()->ip().config('app.key')),
        ]);

        $this->reset(['authorName', 'body']);
        $this->submitted = true;
        unset($this->approvedComments);
    }
};
?>

<div>
    <h2 class="text-xl font-semibold">கருத்துகள் ({{ $this->approvedComments->count() }})</h2>

    <div class="mt-6 space-y-6">
        @forelse ($this->approvedComments as $comment)
            <div class="border-b border-gray-100 pb-4 dark:border-gray-800">
                <p class="text-sm font-medium">{{ $comment->author_display_name }}</p>
                <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->body }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">இதுவரை கருத்துகள் இல்லை.</p>
        @endforelse
    </div>

    <div class="mt-8 rounded-lg border border-gray-200 p-5 dark:border-gray-800">
        @if ($submitted)
            <p class="text-green-700 dark:text-green-400">
                உங்கள் கருத்து சமர்ப்பிக்கப்பட்டது. மதிப்பாய்வுக்குப் பிறகு வெளியிடப்படும்.
            </p>
        @else
            <form wire:submit="submit" class="space-y-3">
                <div>
                    <input
                        type="text"
                        wire:model="authorName"
                        placeholder="உங்கள் பெயர் (புனைப்பெயராகவும் இருக்கலாம்)"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                    >
                    @error('authorName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <textarea
                        wire:model="body"
                        rows="3"
                        placeholder="உங்கள் கருத்து..."
                        class="w-full rounded-md border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                    ></textarea>
                    @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900">
                    சமர்ப்பிக்கவும்
                </button>
            </form>
        @endif
    </div>
</div>
