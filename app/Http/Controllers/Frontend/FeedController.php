<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function index(): Response
    {
        $articles = Article::published()
            ->with('authors')
            ->latest('published_at')
            ->limit(30)
            ->get();

        return response()
            ->view('frontend.feed', ['articles' => $articles])
            ->header('Content-Type', 'application/rss+xml');
    }
}
