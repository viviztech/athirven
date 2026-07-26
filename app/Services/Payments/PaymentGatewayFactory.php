<?php

namespace App\Services\Payments;

use App\Enums\PaymentGateway;

class PaymentGatewayFactory
{
    public static function make(PaymentGateway $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            PaymentGateway::Stripe => app(CashierStripeGateway::class),
            PaymentGateway::Razorpay => app(RazorpayGateway::class),
        };
    }
}
