<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ArticleType: string implements HasLabel
{
    case Editorial = 'editorial';
    case Interview = 'interview';
    case Essay = 'essay';
    case Poem = 'poem';
    case BookReview = 'book_review';
    case Cartoon = 'cartoon';
    case CoverStory = 'cover_story';
    case News = 'news';
    case Opinion = 'opinion';

    public function getLabel(): string
    {
        return match ($this) {
            self::Editorial => 'Editorial',
            self::Interview => 'Interview',
            self::Essay => 'Essay',
            self::Poem => 'Poem',
            self::BookReview => 'Book Review',
            self::Cartoon => 'Cartoon',
            self::CoverStory => 'Cover Story',
            self::News => 'News',
            self::Opinion => 'Opinion',
        };
    }
}
