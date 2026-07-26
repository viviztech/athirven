<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Issue;

class HomeController extends Controller
{
    /**
     * @var array<int, string> excludes the longText `body` column — the
     * homepage only renders article-card.blade.php, which never shows it.
     */
    private const ARTICLE_LIST_COLUMNS = [
        'id', 'issue_id', 'category_id', 'type', 'status',
        'title', 'slug', 'subtitle', 'excerpt', 'is_premium', 'published_at',
    ];

    public function index()
    {
        $latestIssue = Issue::published()
            ->with(['articles' => fn ($query) => $query->published()
                ->select(self::ARTICLE_LIST_COLUMNS)
                ->with('authors', 'category')])
            ->latest('publish_date')
            ->first();

        $recentArticles = $latestIssue?->articles ?? collect();

        return view('frontend.home', [
            'latestIssue' => $latestIssue,
            'recentArticles' => $recentArticles,
        ]);
    }
}
