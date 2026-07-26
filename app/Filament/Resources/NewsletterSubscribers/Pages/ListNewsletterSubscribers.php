<?php

namespace App\Filament\Resources\NewsletterSubscribers\Pages;

use App\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use App\Jobs\SendNewsletterDigestJob;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterSubscribers extends ListRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendDigestNow')
                ->label('Send digest now')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Sends every published article not yet included in a digest to all active subscribers.')
                ->action(function () {
                    SendNewsletterDigestJob::dispatchSync();
                    Notification::make()->title('Digest sent')->success()->send();
                }),
        ];
    }
}
