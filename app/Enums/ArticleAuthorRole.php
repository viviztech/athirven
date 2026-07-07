<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ArticleAuthorRole: string implements HasLabel
{
    case Author = 'author';
    case CoAuthor = 'co_author';
    case Translator = 'translator';
    case Illustrator = 'illustrator';
    case Interviewee = 'interviewee';

    public function getLabel(): string
    {
        return match ($this) {
            self::Author => 'Author',
            self::CoAuthor => 'Co-Author',
            self::Translator => 'Translator',
            self::Illustrator => 'Illustrator',
            self::Interviewee => 'Interviewee',
        };
    }
}
