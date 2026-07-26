<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DonationStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Refunded => 'Refunded',
            self::Failed => 'Failed',
        };
    }
}
