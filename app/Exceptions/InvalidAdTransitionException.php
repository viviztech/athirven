<?php

namespace App\Exceptions;

use App\Enums\AdStatus;
use RuntimeException;

class InvalidAdTransitionException extends RuntimeException
{
    public static function forTransition(AdStatus $from, AdStatus $to): self
    {
        return new self("Cannot transition an ad from [{$from->value}] to [{$to->value}].");
    }
}
