<?php

namespace App\Policies;

use App\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionPlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function view(User $user, SubscriptionPlan $plan): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function update(User $user, SubscriptionPlan $plan): bool
    {
        return $user->can('subscriptions.manage');
    }

    public function delete(User $user, SubscriptionPlan $plan): bool
    {
        return $user->can('subscriptions.manage');
    }
}
