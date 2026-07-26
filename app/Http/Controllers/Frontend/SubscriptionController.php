<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\SubscriptionStatus;
use App\Enums\SubscriptionTier;
use App\Exceptions\PaymentGatewayNotConfiguredException;
use App\Http\Controllers\Controller;
use App\Models\MagazineSubscription;
use App\Models\SubscriptionPlan;
use App\Services\Payments\PaymentGatewayFactory;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('tier')
            ->get()
            ->groupBy(fn (SubscriptionPlan $plan) => $plan->tier->value);

        return view('frontend.subscriptions.index', ['plans' => $plans]);
    }

    public function show(SubscriptionPlan $plan)
    {
        return view('frontend.subscriptions.show', ['plan' => $plan]);
    }

    public function checkout(Request $request, SubscriptionPlan $plan)
    {
        $user = $request->user();

        $shipping = [];

        if ($plan->tier === SubscriptionTier::PrintDigital) {
            $shipping = $request->validate([
                'shipping_name' => ['required', 'string', 'max:255'],
                'shipping_line1' => ['required', 'string', 'max:255'],
                'shipping_line2' => ['nullable', 'string', 'max:255'],
                'shipping_city' => ['required', 'string', 'max:255'],
                'shipping_state' => ['required', 'string', 'max:255'],
                'shipping_postal_code' => ['required', 'string', 'max:255'],
                'shipping_country' => ['required', 'string', 'max:255'],
                'shipping_phone' => ['required', 'string', 'max:255'],
            ]);
        }

        $subscription = MagazineSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => SubscriptionStatus::Pending,
            ...$shipping,
        ]);

        try {
            $session = PaymentGatewayFactory::make($plan->gateway)
                ->createSubscriptionCheckout($user, $plan, $subscription);
        } catch (PaymentGatewayNotConfiguredException) {
            $subscription->delete();

            return back()->with('error', 'இந்த பணம் செலுத்தும் முறை இன்னும் அமைக்கப்படவில்லை. (This payment gateway has no API keys configured yet.)');
        }

        if ($session->mode === 'redirect') {
            return redirect()->away($session->redirectUrl);
        }

        return view('frontend.payments.gateway-checkout', [
            'payload' => $session->clientPayload,
            'successUrl' => route('subscriptions.success'),
            'cancelUrl' => route('subscriptions.cancelled'),
        ]);
    }

    public function success()
    {
        return view('frontend.subscriptions.success');
    }

    public function cancelled()
    {
        return view('frontend.subscriptions.cancelled');
    }
}
