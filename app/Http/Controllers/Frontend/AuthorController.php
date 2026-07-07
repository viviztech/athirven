<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{
    public function show(Author $author)
    {
        $articles = $author->articles()->published()->with('category', 'authors')->latest('published_at')->paginate(10);

        return view('frontend.authors.show', [
            'author' => $author,
            'articles' => $articles,
        ]);
    }
}
