<?php

namespace App\Services\Payments;

use App\Models\Donation;
use App\Models\MagazineSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;

interface PaymentGatewayInterface
{
    /**
     * @throws \App\Exceptions\PaymentGatewayNotConfiguredException
     */
    public function createSubscriptionCheckout(
        User $user,
        SubscriptionPlan $plan,
        MagazineSubscription $subscription
    ): PaymentCheckoutSession;

    /**
     * @throws \App\Exceptions\PaymentGatewayNotConfiguredException
     */
    public function createDonationCheckout(Donation $donation): PaymentCheckoutSession;

    /**
     * Cancels the subscription with the gateway and marks it cancelled locally.
     */
    public function cancelSubscription(MagazineSubscription $subscription): void;
}
