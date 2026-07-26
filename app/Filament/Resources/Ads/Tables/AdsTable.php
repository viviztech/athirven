<?php

namespace App\Filament\Resources\Ads\Tables;

use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use App\Models\Ad;
use App\Services\AdWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sponsor_name')
                    ->label('Sponsor')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('placement')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
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
                    ->options(AdStatus::class),
                SelectFilter::make('placement')
                    ->options(AdPlacement::class),
            ])
            ->recordActions([
                EditAction::make(),
                ...static::workflowActions(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<int, Action>
     */
    public static function workflowActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Ad $record) => static::can($record, AdStatus::Approved))
                ->action(function (Ad $record) {
                    app(AdWorkflowService::class)->transition($record, AdStatus::Approved, auth()->user());
                    Notification::make()->title('Ad approved')->success()->send();
                }),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Ad $record) => static::can($record, AdStatus::Rejected))
                ->action(function (Ad $record) {
                    app(AdWorkflowService::class)->transition($record, AdStatus::Rejected, auth()->user());
                    Notification::make()->title('Ad rejected')->success()->send();
                }),

            Action::make('goLive')
                ->label('Go live')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Ad $record) => static::can($record, AdStatus::Live))
                ->action(function (Ad $record) {
                    app(AdWorkflowService::class)->transition($record, AdStatus::Live, auth()->user());
                    Notification::make()->title('Ad is live')->success()->send();
                }),

            Action::make('expire')
                ->label('Expire')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(fn (Ad $record) => static::can($record, AdStatus::Expired))
                ->action(function (Ad $record) {
                    app(AdWorkflowService::class)->transition($record, AdStatus::Expired, auth()->user());
                    Notification::make()->title('Ad expired')->success()->send();
                }),
        ];
    }

    private static function can(Ad $record, AdStatus $to): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return in_array($to, app(AdWorkflowService::class)->availableTransitions($record, $user), true);
    }
}
