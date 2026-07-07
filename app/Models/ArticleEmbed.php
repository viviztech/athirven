<?php

namespace App\Models;

use App\Enums\ArticleEmbedProvider;
use App\Enums\ArticleEmbedType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['article_id', 'type', 'provider', 'url', 'caption', 'sort_order'])]
class ArticleEmbed extends Model
{
    protected function casts(): array
    {
        return [
            'type' => ArticleEmbedType::class,
            'provider' => ArticleEmbedProvider::class,
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Extracts the 11-character YouTube video ID from watch/short/embed URL
     * forms, restricted to the character set YouTube IDs actually use, so
     * this is safe to interpolate into an iframe src without further escaping.
     */
    public function youtubeVideoId(): ?string
    {
        if (preg_match('/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]{11})/', $this->url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
