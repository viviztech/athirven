<?php

namespace App\Services\Payments;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionStatus;
use App\Exceptions\PaymentGatewayNotConfiguredException;
use App\Models\Donation;
use App\Models\MagazineSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Razorpay\Api\Api;

/**
 * Razorpay Checkout.js is a client-side modal (unlike Stripe's hosted
 * redirect), so these methods return a 'client' PaymentCheckoutSession — the
 * frontend renders Razorpay's script with the returned order/subscription id.
 * Recurring donations are not supported here (Razorpay subscriptions need a
 * pre-provisioned Plan per amount, which doesn't fit freeform donations);
 * that's Stripe-only, enforced by the caller before this class is reached.
 */
class RazorpayGateway implements PaymentGatewayInterface
{
    public function createSubscriptionCheckout(
        User $user,
        SubscriptionPlan $plan,
        MagazineSubscription $subscription
    ): PaymentCheckoutSession {
        $this->assertConfigured();

        $razorpaySubscription = $this->api()->subscription->create([
            'plan_id' => $plan->razorpay_plan_id,
            'customer_notify' => 1,
            'total_count' => 120, // effectively open-ended (10 years of monthly cycles)
            'notes' => ['magazine_subscription_id' => $subscription->id],
        ]);

        $subscription->update(['razorpay_subscription_id' => $razorpaySubscription['id']]);

        return PaymentCheckoutSession::client([
            'key' => config('services.razorpay.key'),
            'subscription_id' => $razorpaySubscription['id'],
            'name' => 'Athirven',
            'description' => $plan->name_en,
            'prefill' => ['email' => $user->email, 'name' => $user->name],
        ]);
    }

    public function createDonationCheckout(Donation $donation): PaymentCheckoutSession
    {
        $this->assertConfigured();

        $order = $this->api()->order->create([
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'receipt' => "donation_{$donation->id}",
            'notes' => ['donation_id' => $donation->id],
        ]);

        $donation->update(['gateway_reference_id' => $order['id']]);

        return PaymentCheckoutSession::client([
            'key' => config('services.razorpay.key'),
            'order_id' => $order['id'],
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'name' => 'Athirven',
            'description' => 'Donation',
            'prefill' => ['email' => $donation->donor_email, 'name' => $donation->donor_name],
        ]);
    }

    public function cancelSubscription(MagazineSubscription $subscription): void
    {
        $this->assertConfigured();

        if ($subscription->razorpay_subscription_id) {
            $this->api()->subscription->fetch($subscription->razorpay_subscription_id)->cancel();
        }

        $subscription->update([
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }

    private function api(): Api
    {
        return new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    private function assertConfigured(): void
    {
        if (blank(config('services.razorpay.key')) || blank(config('services.razorpay.secret'))) {
            throw PaymentGatewayNotConfiguredException::forGateway(PaymentGateway::Razorpay);
        }
    }
}
