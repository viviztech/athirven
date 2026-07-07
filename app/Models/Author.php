<?php

namespace App\Models;

use App\Support\TamilSlugger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['user_id', 'pen_name', 'real_name', 'slug', 'bio', 'social_links', 'is_pseudonymous', 'contact_email'])]
class Author extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Author $author) {
            if (blank($author->slug)) {
                $author->slug = TamilSlugger::unique(static::class, $author->pen_name);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'real_name' => 'encrypted',
            'contact_email' => 'encrypted',
            'social_links' => 'array',
            'is_pseudonymous' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_author')
            ->withPivot(['role', 'sort_order'])
            ->withTimestamps();
    }
}
