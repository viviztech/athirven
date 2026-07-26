<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\MagazineSubscription;
use Illuminate\Console\Command;

class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'app:expire-subscriptions';

    protected $description = 'Flip past_due subscriptions to expired once their grace period has elapsed.';

    private const GRACE_DAYS = 7;

    public function handle(): void
    {
        $count = MagazineSubscription::query()
            ->where('status', SubscriptionStatus::PastDue)
            ->where('current_period_ends_at', '<=', now()->subDays(self::GRACE_DAYS))
            ->update(['status' => SubscriptionStatus::Expired]);

        $this->info("Expired {$count} subscription(s) past their grace period.");
    }
}
