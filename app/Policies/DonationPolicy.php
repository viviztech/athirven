<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('donations.manage');
    }

    public function view(User $user, Donation $donation): bool
    {
        return $user->can('donations.manage');
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->can('donations.manage');
    }

    public function delete(User $user, Donation $donation): bool
    {
        return $user->can('donations.manage');
    }
}
