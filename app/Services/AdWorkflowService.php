<?php

namespace App\Services;

use App\Enums\AdStatus;
use App\Exceptions\InvalidAdTransitionException;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Mirrors ArticleWorkflowService's shape: legal transitions live here, not
 * on the model or a raw Filament form field, even though (unlike articles)
 * a single 'ads.manage' permission gates every transition today.
 */
class AdWorkflowService
{
    /**
     * @var array<string, array<string, string>> from-status => [to-status => required permission]
     */
    private const TRANSITIONS = [
        'pending' => [
            'approved' => 'ads.manage',
            'rejected' => 'ads.manage',
        ],
        'approved' => ['live' => 'ads.manage'],
        'live' => ['expired' => 'ads.manage'],
    ];

    /**
     * @return array<int, AdStatus>
     */
    public function availableTransitions(Ad $ad, User $user): array
    {
        $map = self::TRANSITIONS[$ad->status->value] ?? [];

        return collect($map)
            ->filter(fn (string $permission) => $user->can($permission))
            ->keys()
            ->map(fn (string $value) => AdStatus::from($value))
            ->all();
    }

    public function transition(Ad $ad, AdStatus $to, User $user): Ad
    {
        $from = $ad->status;
        $map = self::TRANSITIONS[$from->value] ?? [];

        if (! array_key_exists($to->value, $map)) {
            throw InvalidAdTransitionException::forTransition($from, $to);
        }

        $permission = $map[$to->value];

        if (! $user->can($permission)) {
            throw new AuthorizationException("Missing permission [{$permission}] to move an ad from [{$from->value}] to [{$to->value}].");
        }

        $ad->status = $to;
        $ad->save();

        return $ad;
    }
}
