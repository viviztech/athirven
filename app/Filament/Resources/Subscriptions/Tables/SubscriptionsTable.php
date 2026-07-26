<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Enums\SubscriptionStatus;
use App\Models\MagazineSubscription;
use App\Services\Payments\PaymentGatewayFactory;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Subscriber')
                    ->searchable(),
                TextColumn::make('plan.name_en')
                    ->label('Plan')
                    ->searchable(),
                TextColumn::make('plan.gateway')
                    ->label('Gateway')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('current_period_ends_at')
                    ->label('Renews / expires')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(SubscriptionStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
                ...static::workflowActions(),
            ]);
    }

    /**
     * @return array<int, Action>
     */
    public static function workflowActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Cancel')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (MagazineSubscription $record) => $record->status === SubscriptionStatus::Active || $record->status === SubscriptionStatus::PastDue)
                ->action(function (MagazineSubscription $record) {
                    PaymentGatewayFactory::make($record->plan->gateway)->cancelSubscription($record);
                    Notification::make()->title('Subscription cancelled')->success()->send();
                }),
        ];
    }
}
