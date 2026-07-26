<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\DonationStatus;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\MagazineSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Utility;
use Symfony\Component\HttpFoundation\Response;

class RazorpayWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $secret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');

        if (blank($secret) || blank($signature)) {
            return new Response('Razorpay webhook secret not configured.', 200);
        }

        try {
            (new Utility)->verifyWebhookSignature($request->getContent(), $signature, $secret);
        } catch (SignatureVerificationError $e) {
            abort(400, 'Invalid Razorpay webhook signature.');
        }

        $payload = $request->json()->all();

        match ($payload['event'] ?? null) {
            'subscription.activated', 'subscription.charged' => $this->syncSubscription($payload),
            'subscription.cancelled', 'subscription.completed', 'subscription.halted' => $this->cancelSubscription($payload),
            'payment.captured' => $this->completeDonation($payload),
            default => null,
        };

        return new Response('Webhook Handled', 200);
    }

    private function syncSubscription(array $payload): void
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;

        if (! $entity) {
            return;
        }

        MagazineSubscription::where('razorpay_subscription_id', $entity['id'])->update([
            'status' => SubscriptionStatus::Active,
            'current_period_ends_at' => isset($entity['current_end'])
                ? Carbon::createFromTimestamp($entity['current_end'])
                : null,
        ]);
    }

    private function cancelSubscription(array $payload): void
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;

        if (! $entity) {
            return;
        }

        MagazineSubscription::where('razorpay_subscription_id', $entity['id'])->update([
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }

    private function completeDonation(array $payload): void
    {
        $entity = $payload['payload']['payment']['entity'] ?? null;
        $orderId = $entity['order_id'] ?? null;

        if (! $orderId) {
            return;
        }

        Donation::where('gateway_reference_id', $orderId)->update([
            'status' => DonationStatus::Completed,
        ]);
    }
}
