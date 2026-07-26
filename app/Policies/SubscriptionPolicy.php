<?php

namespace App\Policies;

use App\Models\MagazineSubscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function view(User $user, MagazineSubscription $subscription): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function update(User $user, MagazineSubscription $subscription): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function delete(User $user, MagazineSubscription $subscription): bool
    {
        return $user->can('subscriptions.manage');
    }
}
