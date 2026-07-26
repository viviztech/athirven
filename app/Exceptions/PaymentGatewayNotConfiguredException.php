<?php

namespace App\Exceptions;

use App\Enums\PaymentGateway;
use RuntimeException;

/**
 * Thrown when a checkout is attempted against a gateway with no API keys set
 * yet (Phase 4 ships gateway-agnostic, wired to blank .env placeholders).
 * Caught at the controller boundary and shown as a friendly notice rather
 * than a raw SDK exception.
 */
class PaymentGatewayNotConfiguredException extends RuntimeException
{
    public static function forGateway(PaymentGateway $gateway): self
    {
        return new self("The [{$gateway->value}] payment gateway has no API keys configured yet.");
    }
}
