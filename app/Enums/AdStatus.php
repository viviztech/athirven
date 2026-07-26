<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AdStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Live = 'live';
    case Expired = 'expired';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Live => 'Live',
            self::Expired => 'Expired',
            self::Rejected => 'Rejected',
        };
    }
}
