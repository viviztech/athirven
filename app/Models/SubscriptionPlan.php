<?php

namespace App\Models;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionTier;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'name_ta', 'name_en', 'tier', 'gateway', 'interval',
    'amount', 'currency', 'stripe_price_id', 'razorpay_plan_id', 'is_active',
])]
class SubscriptionPlan extends Model
{
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function casts(): array
    {
        return [
            'tier' => SubscriptionTier::class,
            'gateway' => PaymentGateway::class,
            'amount' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MagazineSubscription::class);
    }
}
