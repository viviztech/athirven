<?php

namespace App\Jobs;

use App\Mail\NewsletterDigestMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Batches every published article that hasn't gone out in a digest yet into
 * ONE email per active subscriber, rather than emailing per-article — see
 * the Phase 5 plan's scoping note on why the newsletter is decoupled from
 * ArticlePublished (unlike the Telegram listener, which does fire per article).
 */
class SendNewsletterDigestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function handle(): void
    {
        $articles = Article::published()
            ->whereNull('digest_sent_at')
            ->orderBy('published_at')
            ->get();

        if ($articles->isEmpty()) {
            return;
        }

        NewsletterSubscriber::query()
            ->where('is_active', true)
            ->each(fn (NewsletterSubscriber $subscriber) => Mail::to($subscriber->email)
                ->send(new NewsletterDigestMail($articles, $subscriber)));

        Article::whereIn('id', $articles->pluck('id'))->update(['digest_sent_at' => now()]);
    }
}
