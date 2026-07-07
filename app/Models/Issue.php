<?php

namespace App\Models;

use App\Enums\IssueStatus;
use App\Support\TamilSlugger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['issue_number', 'title', 'slug', 'month', 'year', 'publish_date', 'status', 'is_premium'])]
class Issue extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', IssueStatus::Published);
    }

    protected static function booted(): void
    {
        static::creating(function (Issue $issue) {
            if (blank($issue->slug)) {
                $issue->slug = TamilSlugger::unique(static::class, $issue->title);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'status' => IssueStatus::class,
            'is_premium' => 'boolean',
            'publish_date' => 'date',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')->singleFile();
        $this->addMediaCollection('issue_pdf')->singleFile();
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class)->orderBy('order');
    }
}
