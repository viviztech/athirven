<?php

namespace App\Models;

use App\Support\TamilSlugger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name_ta', 'name_en', 'slug'])]
class Tag extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Tag $tag) {
            if (blank($tag->slug)) {
                $tag->slug = TamilSlugger::unique(static::class, $tag->name_ta);
            }
        });
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
