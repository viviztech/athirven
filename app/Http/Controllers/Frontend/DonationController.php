<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\DonationStatus;
use App\Enums\PaymentGateway;
use App\Exceptions\PaymentGatewayNotConfiguredException;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\Payments\PaymentGatewayFactory;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        return view('frontend.donations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'in:usd,inr'],
            'gateway' => ['required', 'in:stripe,razorpay'],
            'is_anonymous' => ['boolean'],
            'is_recurring' => ['boolean'],
        ]);

        $gateway = PaymentGateway::from($data['gateway']);

        if ($gateway === PaymentGateway::Razorpay && ($data['is_recurring'] ?? false)) {
            return back()->withErrors([
                'is_recurring' => 'Recurring donations are currently only available via Stripe.',
            ])->withInput();
        }

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'donor_name' => $data['donor_name'] ?? null,
            'donor_email' => $data['donor_email'] ?? null,
            'amount' => (int) round($data['amount'] * 100),
            'currency' => $data['currency'],
            'gateway' => $gateway,
            'status' => DonationStatus::Pending,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'is_recurring' => $data['is_recurring'] ?? false,
        ]);

        try {
            $session = PaymentGatewayFactory::make($gateway)->createDonationCheckout($donation);
        } catch (PaymentGatewayNotConfiguredException) {
            $donation->delete();

            return back()->with('error', 'இந்த பணம் செலுத்தும் முறை இன்னும் அமைக்கப்படவில்லை. (This payment gateway has no API keys configured yet.)');
        }

        if ($session->mode === 'redirect') {
            return redirect()->away($session->redirectUrl);
        }

        return view('frontend.payments.gateway-checkout', [
            'payload' => $session->clientPayload,
            'successUrl' => route('donations.success'),
            'cancelUrl' => route('donations.index'),
        ]);
    }

    public function success()
    {
        return view('frontend.donations.success');
    }
}
