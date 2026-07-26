<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines))->header('Content-Type', 'text/plain');
    }
}
