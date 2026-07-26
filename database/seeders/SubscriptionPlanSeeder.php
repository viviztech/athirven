<?php

namespace Database\Seeders;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionTier;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

/**
 * One row per tier per gateway (docs/architecture.md routes checkout by
 * SubscriptionPlan.gateway). stripe_price_id/razorpay_plan_id are left
 * blank — they must be filled in via the Filament admin once real
 * Stripe Prices / Razorpay Plans exist in each gateway's dashboard.
 */
class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        if (SubscriptionPlan::query()->exists()) {
            $this->command->info('Subscription plans already present, skipping.');

            return;
        }

        $plans = [
            ['code' => 'digital-monthly-stripe', 'name_ta' => 'டிஜிட்டல் சந்தா — மாதாந்திரம்', 'name_en' => 'Digital Monthly', 'tier' => SubscriptionTier::Digital, 'gateway' => PaymentGateway::Stripe, 'interval' => 'month', 'amount' => 500, 'currency' => 'usd'],
            ['code' => 'digital-monthly-razorpay', 'name_ta' => 'டிஜிட்டல் சந்தா — மாதாந்திரம்', 'name_en' => 'Digital Monthly', 'tier' => SubscriptionTier::Digital, 'gateway' => PaymentGateway::Razorpay, 'interval' => 'month', 'amount' => 30000, 'currency' => 'inr'],

            ['code' => 'print-digital-monthly-stripe', 'name_ta' => 'அச்சு + டிஜிட்டல் — மாதாந்திரம்', 'name_en' => 'Print + Digital Monthly', 'tier' => SubscriptionTier::PrintDigital, 'gateway' => PaymentGateway::Stripe, 'interval' => 'month', 'amount' => 1500, 'currency' => 'usd'],
            ['code' => 'print-digital-monthly-razorpay', 'name_ta' => 'அச்சு + டிஜிட்டல் — மாதாந்திரம்', 'name_en' => 'Print + Digital Monthly', 'tier' => SubscriptionTier::PrintDigital, 'gateway' => PaymentGateway::Razorpay, 'interval' => 'month', 'amount' => 90000, 'currency' => 'inr'],

            ['code' => 'patron-yearly-stripe', 'name_ta' => 'புரவலர் — ஆண்டுதோறும்', 'name_en' => 'Patron Yearly', 'tier' => SubscriptionTier::Patron, 'gateway' => PaymentGateway::Stripe, 'interval' => 'year', 'amount' => 10000, 'currency' => 'usd'],
            ['code' => 'patron-yearly-razorpay', 'name_ta' => 'புரவலர் — ஆண்டுதோறும்', 'name_en' => 'Patron Yearly', 'tier' => SubscriptionTier::Patron, 'gateway' => PaymentGateway::Razorpay, 'interval' => 'year', 'amount' => 500000, 'currency' => 'inr'],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan + ['is_active' => true]);
        }

        $this->command->info('Subscription plans seeded successfully.');
    }
}
