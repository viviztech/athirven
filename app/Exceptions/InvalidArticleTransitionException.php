<?php

namespace App\Exceptions;

use App\Enums\ArticleStatus;
use RuntimeException;

class InvalidArticleTransitionException extends RuntimeException
{
    public static function forTransition(ArticleStatus $from, ArticleStatus $to): self
    {
        return new self("Cannot transition an article from [{$from->value}] to [{$to->value}].");
    }
}
