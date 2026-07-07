<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $articles = $tag->articles()->published()->with('authors', 'category')->latest('published_at')->paginate(10);

        return view('frontend.tags.show', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }
}
