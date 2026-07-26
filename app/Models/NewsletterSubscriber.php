<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['email', 'unsubscribe_token', 'subscribed_at', 'is_active'])]
class NewsletterSubscriber extends Model
{
    public function getRouteKeyName(): string
    {
        return 'unsubscribe_token';
    }

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber) {
            if (blank($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = Str::random(40);
            }

            if (blank($subscriber->subscribed_at)) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
