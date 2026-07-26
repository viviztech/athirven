<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'subscription_plan_id', 'status', 'stripe_subscription_id', 'razorpay_subscription_id',
    'current_period_ends_at', 'cancelled_at',
    'shipping_name', 'shipping_line1', 'shipping_line2', 'shipping_city', 'shipping_state',
    'shipping_postal_code', 'shipping_country', 'shipping_phone',
])]
class MagazineSubscription extends Model
{
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', SubscriptionStatus::Active);
    }

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'current_period_ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
