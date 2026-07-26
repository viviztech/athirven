<?php

namespace App\Models;

use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'created_by_id', 'sponsor_name', 'title', 'target_url', 'placement',
    'status', 'starts_at', 'ends_at', 'price_paid', 'currency',
])]
class Ad extends Model implements HasMedia
{
    use InteractsWithMedia;

    #[Scope]
    protected function live(Builder $query): void
    {
        $query->where('status', AdStatus::Live)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    protected function casts(): array
    {
        return [
            'placement' => AdPlacement::class,
            'status' => AdStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('creative')->singleFile()->useDisk('public');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
