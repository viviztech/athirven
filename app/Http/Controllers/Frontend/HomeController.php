<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Issue;

class HomeController extends Controller
{
    public function index()
    {
        $latestIssue = Issue::published()
            ->with(['articles' => fn ($query) => $query->published()->with('authors', 'category')])
            ->latest('publish_date')
            ->first();

        $recentArticles = $latestIssue?->articles ?? collect();

        return view('frontend.home', [
            'latestIssue' => $latestIssue,
            'recentArticles' => $recentArticles,
        ]);
    }
}
