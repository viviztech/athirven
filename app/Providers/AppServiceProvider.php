<?php

namespace App\Providers;

use App\Events\ArticlePublished;
use App\Listeners\PostArticleToTelegramChannel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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

        Password::defaults(fn () => Password::min(8)->uncompromised());

        RateLimiter::for('login', fn ($request) => Limit::perMinute(5)->by(
            strtolower((string) $request->input('email')).'|'.$request->ip()
        ));

        RateLimiter::for('register', fn ($request) => Limit::perMinute(5)->by($request->ip()));

        RateLimiter::for('donations', fn ($request) => Limit::perMinute(10)->by($request->ip()));

        RateLimiter::for('newsletter', fn ($request) => Limit::perMinute(10)->by($request->ip()));
    }
}
