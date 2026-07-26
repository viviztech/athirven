<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\DonationStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Donation;
use App\Models\MagazineSubscription;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController;

/**
 * Extends Cashier's own webhook controller (which keeps its internal
 * subscriptions/subscription_items tables in sync) and additionally syncs
 * our own domain tables (magazine_subscriptions/donations), since checkout
 * sessions here are built directly against the Stripe API rather than via
 * Cashier's SubscriptionBuilder — see CashierStripeGateway.
 */
class StripeWebhookController extends WebhookController
{
    protected function handleCheckoutSessionCompleted(array $payload)
    {
        $session = $payload['data']['object'];
        $metadata = $session['metadata'] ?? [];

        if (($session['mode'] ?? null) === 'subscription' && isset($metadata['magazine_subscription_id'])) {
            MagazineSubscription::where('id', $metadata['magazine_subscription_id'])->update([
                'status' => SubscriptionStatus::Active,
                'stripe_subscription_id' => $session['subscription'] ?? null,
            ]);
        }

        if (isset($metadata['donation_id'])) {
            Donation::where('id', $metadata['donation_id'])->update([
                'status' => DonationStatus::Completed,
                'gateway_reference_id' => $session['payment_intent'] ?? $session['subscription'] ?? $session['id'],
            ]);
        }

        return $this->successMethod();
    }

    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        parent::handleCustomerSubscriptionUpdated($payload);

        $data = $payload['data']['object'];

        MagazineSubscription::where('stripe_subscription_id', $data['id'])->get()->each(
            fn (MagazineSubscription $subscription) => $subscription->update([
                'status' => $this->mapStripeStatus($data['status'] ?? null),
                'current_period_ends_at' => isset($data['current_period_end'])
                    ? Carbon::createFromTimestamp($data['current_period_end'])
                    : $subscription->current_period_ends_at,
            ])
        );

        return $this->successMethod();
    }

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $data = $payload['data']['object'];

        MagazineSubscription::where('stripe_subscription_id', $data['id'])->update([
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ]);

        return $this->successMethod();
    }

    private function mapStripeStatus(?string $status): SubscriptionStatus
    {
        return match ($status) {
            'active', 'trialing' => SubscriptionStatus::Active,
            'past_due', 'unpaid' => SubscriptionStatus::PastDue,
            'canceled' => SubscriptionStatus::Cancelled,
            'incomplete_expired' => SubscriptionStatus::Expired,
            default => SubscriptionStatus::Pending,
        };
    }
}
