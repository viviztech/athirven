<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ArticleEmbedType: string implements HasLabel
{
    case Audio = 'audio';
    case Video = 'video';

    public function getLabel(): string
    {
        return match ($this) {
            self::Audio => 'Audio',
            self::Video => 'Video',
        };
    }
}
