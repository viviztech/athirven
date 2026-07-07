<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ArticleEmbedProvider: string implements HasLabel
{
    case YouTube = 'youtube';
    case Upload = 'upload';

    public function getLabel(): string
    {
        return match ($this) {
            self::YouTube => 'YouTube',
            self::Upload => 'Uploaded file',
        };
    }
}
