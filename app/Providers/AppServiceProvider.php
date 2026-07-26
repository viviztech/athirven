<?php

namespace App\Providers;

use App\Events\ArticlePublished;
use App\Listeners\PostArticleToTelegramChannel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn ($user) => $user->hasRole('Admin') ? true : null);

        Event::listen(ArticlePublished::class, PostArticleToTelegramChannel::class);
    }
}
