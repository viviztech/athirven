<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Issue;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticRoutes = [
            ['loc' => route('home'), 'lastmod' => now()],
            ['loc' => route('issues.index'), 'lastmod' => now()],
            ['loc' => route('categories.index'), 'lastmod' => now()],
            ['loc' => route('search'), 'lastmod' => now()],
            ['loc' => route('subscriptions.index'), 'lastmod' => now()],
            ['loc' => route('donations.index'), 'lastmod' => now()],
        ];

        $articles = Article::published()->get()->map(fn (Article $article) => [
            'loc' => route('articles.show', $article),
            'lastmod' => $article->updated_at,
        ]);

        $issues = Issue::published()->get()->map(fn (Issue $issue) => [
            'loc' => route('issues.show', $issue),
            'lastmod' => $issue->updated_at,
        ]);

        $categories = Category::all()->map(fn (Category $category) => [
            'loc' => route('categories.show', $category),
            'lastmod' => $category->updated_at,
        ]);

        $authors = Author::all()->map(fn (Author $author) => [
            'loc' => route('authors.show', $author),
            'lastmod' => $author->updated_at,
        ]);

        $urls = collect($staticRoutes)
            ->concat($articles)
            ->concat($issues)
            ->concat($categories)
            ->concat($authors);

        return response()
            ->view('frontend.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
