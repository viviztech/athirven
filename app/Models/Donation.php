<?php

namespace App\Models;

use App\Enums\DonationStatus;
use App\Enums\PaymentGateway;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'donor_name', 'donor_email', 'amount', 'currency', 'gateway',
    'gateway_reference_id', 'status', 'is_anonymous', 'is_recurring',
])]
class Donation extends Model
{
    protected function casts(): array
    {
        return [
            'gateway' => PaymentGateway::class,
            'status' => DonationStatus::class,
            'amount' => 'integer',
            'is_anonymous' => 'boolean',
            'is_recurring' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
