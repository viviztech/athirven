<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubscriptionTier: string implements HasLabel
{
    case Digital = 'digital';
    case PrintDigital = 'print_digital';
    case Patron = 'patron';

    public function getLabel(): string
    {
        return match ($this) {
            self::Digital => 'Digital Subscriber',
            self::PrintDigital => 'Print + Digital',
            self::Patron => 'Patron',
        };
    }
}
