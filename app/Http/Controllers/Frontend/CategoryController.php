<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('name_ta')
            ->get();

        return view('frontend.categories.index', ['categories' => $categories]);
    }

    public function show(Category $category)
    {
        $articles = $category->articles()->published()->with('authors')->latest('published_at')->paginate(10);

        return view('frontend.categories.show', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
