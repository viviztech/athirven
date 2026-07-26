<?php

namespace App\Services\Telegram;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Plain REST calls to the Telegram Bot API — no SDK needed, the API surface
 * used here (sendMessage) is a single JSON POST. No-ops with a log line when
 * unconfigured rather than throwing: this is a background reach feature, not
 * a user-facing flow that needs a friendly error (contrast with the payment
 * gateways in app/Services/Payments, which throw PaymentGatewayNotConfiguredException).
 */
class TelegramBotService
{
    public function postArticlePublished(Article $article): void
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.channel_chat_id');

        if (blank($botToken) || blank($chatId)) {
            Log::info("Telegram bot not configured; skipping channel post for article #{$article->id}.");

            return;
        }

        $summary = Str::limit(strip_tags($article->excerpt ?: $article->body), 200);
        $text = "{$article->title}\n\n{$summary}\n\n".route('articles.show', $article);

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
