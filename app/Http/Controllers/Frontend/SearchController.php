<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index()
    {
        return view('frontend.search');
    }
}
