<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use App\Services\Telegram\TelegramBotService;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostArticleToTelegramChannel implements ShouldQueue
{
    public function handle(ArticlePublished $event): void
    {
        app(TelegramBotService::class)->postArticlePublished($event->article);
    }
}
