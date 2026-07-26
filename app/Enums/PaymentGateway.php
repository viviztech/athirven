<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentGateway: string implements HasLabel
{
    case Stripe = 'stripe';
    case Razorpay = 'razorpay';

    public function getLabel(): string
    {
        return match ($this) {
            self::Stripe => 'Stripe',
            self::Razorpay => 'Razorpay',
        };
    }
}
