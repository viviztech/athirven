<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Laravel's Str::slug() transliterates through Str::ascii(), which strips the
 * Tamil Unicode block (U+0B80–U+0BFF) entirely, producing an empty slug for
 * Tamil-only titles. This preserves Tamil script and Latin alphanumerics
 * in-place instead, so URLs stay readable and shareable in Tamil.
 */
class TamilSlugger
{
    public static function make(string $title): string
    {
        // Keep Tamil script (\p{Tamil}), Latin letters, digits; collapse everything else to "-".
        $slug = preg_replace('/[^\p{Tamil}a-zA-Z0-9]+/u', '-', trim($title));
        $slug = mb_strtolower($slug, 'UTF-8');

        return trim($slug, '-');
    }

    /**
     * Generate a slug that is unique for the given model/column, appending
     * -2, -3, ... on collision (ignoring the given model id, for updates).
     */
    public static function unique(string $modelClass, string $title, string $column = 'slug', ?int $ignoreId = null): string
    {
        $base = static::make($title);
        $slug = $base;
        $i = 2;

        /** @var Model $modelClass */
        while (
            $modelClass::query()
                ->where($column, $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
