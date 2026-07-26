<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['is_active' => true]
        );

        return back()->with('status', 'சந்தா செய்யப்பட்டது! நன்றி.');
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update(['is_active' => false]);

        return redirect()->route('home')->with('status', 'குழுசேர்வு நிறுத்தப்பட்டது.');
    }
}
