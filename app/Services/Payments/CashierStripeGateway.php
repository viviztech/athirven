<?php

namespace App\Services\Payments;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionStatus;
use App\Exceptions\PaymentGatewayNotConfiguredException;
use App\Models\Donation;
use App\Models\MagazineSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Stripe\StripeClient;

/**
 * Wraps Laravel Cashier for the diaspora/international payment path. Builds
 * Stripe Checkout Sessions directly (rather than via Cashier's
 * SubscriptionBuilder) so both the subscription and donation flows return a
 * plain Stripe Session with a guaranteed ->url, and so guest donors (no
 * User/Stripe customer) can check out too.
 */
class CashierStripeGateway implements PaymentGatewayInterface
{
    public function createSubscriptionCheckout(
        User $user,
        SubscriptionPlan $plan,
        MagazineSubscription $subscription
    ): PaymentCheckoutSession {
        $this->assertConfigured();

        $customer = $user->createOrGetStripeCustomer();

        $session = $user->stripe()->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $customer->id,
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'success_url' => route('subscriptions.success'),
            'cancel_url' => route('subscriptions.cancelled'),
            'client_reference_id' => (string) $subscription->id,
            'metadata' => ['magazine_subscription_id' => $subscription->id],
            'subscription_data' => [
                'metadata' => ['magazine_subscription_id' => $subscription->id],
            ],
        ]);

        return PaymentCheckoutSession::redirect($session->url);
    }

    public function createDonationCheckout(Donation $donation): PaymentCheckoutSession
    {
        $this->assertConfigured();

        $stripe = $donation->user
            ? $donation->user->stripe()
            : new StripeClient(config('cashier.secret'));

        $lineItem = [
            'price_data' => [
                'currency' => $donation->currency,
                'product_data' => ['name' => 'Athirven Donation'],
                'unit_amount' => $donation->amount,
            ],
            'quantity' => 1,
        ];

        if ($donation->is_recurring) {
            $lineItem['price_data']['recurring'] = ['interval' => 'month'];
        }

        $params = [
            'mode' => $donation->is_recurring ? 'subscription' : 'payment',
            'line_items' => [$lineItem],
            'success_url' => route('donations.success'),
            'cancel_url' => route('donations.index'),
            'client_reference_id' => (string) $donation->id,
            'metadata' => ['donation_id' => $donation->id],
        ];

        if ($donation->user) {
            $params['customer'] = $donation->user->createOrGetStripeCustomer()->id;
        } elseif ($donation->donor_email) {
            $params['customer_email'] = $donation->donor_email;
        }

        $session = $stripe->checkout->sessions->create($params);

        return PaymentCheckoutSession::redirect($session->url);
    }

    public function cancelSubscription(MagazineSubscription $subscription): void
    {
        $this->assertConfigured();

        if ($subscription->stripe_subscription_id) {
            (new StripeClient(config('cashier.secret')))
                ->subscriptions
                ->cancel($subscription->stripe_subscription_id);
        }

        $subscription->update([
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }

    private function assertConfigured(): void
    {
        if (blank(config('cashier.key')) || blank(config('cashier.secret'))) {
            throw PaymentGatewayNotConfiguredException::forGateway(PaymentGateway::Stripe);
        }
    }
}
