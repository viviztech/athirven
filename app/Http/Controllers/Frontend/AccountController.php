<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MagazineSubscription;
use App\Services\Payments\PaymentGatewayFactory;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $subscription = $user->magazineSubscriptions()->with('plan')->latest()->first();
        $donations = $user->donations()->latest()->get();

        return view('frontend.account.index', [
            'subscription' => $subscription,
            'donations' => $donations,
        ]);
    }

    public function cancelSubscription(Request $request, MagazineSubscription $subscription)
    {
        abort_unless($subscription->user_id === $request->user()->id, 403);

        PaymentGatewayFactory::make($subscription->plan->gateway)->cancelSubscription($subscription);

        return redirect()->route('account')->with('status', 'உங்கள் சந்தா ரத்து செய்யப்பட்டது.');
    }
}
