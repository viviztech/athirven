<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Enums\ArticleType;
use App\Support\TamilSlugger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'issue_id', 'category_id', 'created_by_id', 'type', 'status', 'title', 'slug', 'subtitle', 'excerpt', 'body',
    'is_premium', 'reading_time_minutes', 'order', 'published_at', 'scheduled_at', 'submitted_at',
    'proofread_at', 'proofread_by_id', 'proofreader_notes', 'revision_notes',
    'allow_comments', 'comment_moderation_mode', 'meta_title', 'meta_description',
])]
class Article extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', ArticleStatus::Published)
            ->where('published_at', '<=', now());
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (blank($article->slug)) {
                $article->slug = TamilSlugger::unique(static::class, $article->title);
            }

            if (blank($article->created_by_id) && auth()->check()) {
                $article->created_by_id = auth()->id();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'type' => ArticleType::class,
            'status' => ArticleStatus::class,
            'is_premium' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'submitted_at' => 'datetime',
            'proofread_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('audio_narration');
        $this->addMediaCollection('gallery');
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'article_author')
            ->withPivot(['role', 'sort_order'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function embeds(): HasMany
    {
        return $this->hasMany(ArticleEmbed::class)->orderBy('sort_order');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function proofreadBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proofread_by_id');
    }
}
