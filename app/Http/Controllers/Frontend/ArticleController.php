<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        abort_unless($article->status === ArticleStatus::Published, 404);

        $article->load(['issue', 'category', 'tags', 'authors', 'embeds']);

        // No reader accounts/subscriptions exist yet (that's Phase 4), so every
        // premium article is gated for every visitor until real entitlement
        // checks replace this constant.
        $isEntitled = false;

        return view('frontend.articles.show', [
            'article' => $article,
            'isEntitled' => $isEntitled,
        ]);
    }
}
