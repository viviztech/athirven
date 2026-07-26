<?php

namespace App\Services\Payments;

/**
 * Stripe Checkout is a hosted redirect; Razorpay Checkout.js is a client-side
 * modal driven by an order/subscription id. This DTO lets both gateways
 * return something the frontend can act on without the caller needing to
 * know which gateway it dealt with.
 */
class PaymentCheckoutSession
{
    /**
     * @param  'redirect'|'client'  $mode
     * @param  array<string, mixed>|null  $clientPayload
     */
    public function __construct(
        public readonly string $mode,
        public readonly ?string $redirectUrl = null,
        public readonly ?array $clientPayload = null,
    ) {}

    public static function redirect(string $url): self
    {
        return new self(mode: 'redirect', redirectUrl: $url);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function client(array $payload): self
    {
        return new self(mode: 'client', clientPayload: $payload);
    }
}
