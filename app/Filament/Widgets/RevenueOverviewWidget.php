<?php

namespace App\Filament\Widgets;

use App\Enums\AdStatus;
use App\Enums\DonationStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Ad;
use App\Models\Donation;
use App\Models\MagazineSubscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->can('revenue.view') ?? false;
    }

    protected function getStats(): array
    {
        $activeSubscriptions = MagazineSubscription::query()
            ->where('status', SubscriptionStatus::Active)
            ->with('plan')
            ->get();

        $mrr = $activeSubscriptions->sum(function (MagazineSubscription $subscription) {
            $amount = $subscription->plan->amount / 100;

            return $subscription->plan->interval === 'year' ? $amount / 12 : $amount;
        });

        $donationsThisMonth = Donation::query()
            ->where('status', DonationStatus::Completed)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') / 100;

        $adRevenueThisMonth = Ad::query()
            ->whereIn('status', [AdStatus::Approved, AdStatus::Live])
            ->whereMonth('starts_at', now()->month)
            ->whereYear('starts_at', now()->year)
            ->sum('price_paid') / 100;

        return [
            Stat::make('Active subscriptions', $activeSubscriptions->count()),
            Stat::make('Est. MRR', number_format($mrr, 2)),
            Stat::make('Donations this month', number_format($donationsThisMonth, 2)),
            Stat::make('Ad revenue this month', number_format($adRevenueThisMonth, 2)),
        ];
    }
}
