<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ArticleStatus;
use App\Enums\IssueStatus;
use App\Http\Controllers\Controller;
use App\Models\Issue;

class HomeController extends Controller
{
    public function index()
    {
        $latestIssue = Issue::query()
            ->where('status', IssueStatus::Published)
            ->with(['articles' => fn ($query) => $query->where('status', ArticleStatus::Published)->with('authors', 'category')])
            ->latest('publish_date')
            ->first();

        $recentArticles = $latestIssue?->articles ?? collect();

        return view('frontend.home', [
            'latestIssue' => $latestIssue,
            'recentArticles' => $recentArticles,
        ]);
    }
}
