<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\IssueStatus;
use App\Http\Controllers\Controller;
use App\Models\Issue;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::published()
            ->orderByDesc('publish_date')
            ->paginate(12);

        return view('frontend.issues.index', ['issues' => $issues]);
    }

    public function show(Issue $issue)
    {
        abort_unless($issue->status === IssueStatus::Published, 404);

        $issue->load(['articles' => fn ($query) => $query->published()->with('authors', 'category')]);

        return view('frontend.issues.show', ['issue' => $issue]);
    }
}
