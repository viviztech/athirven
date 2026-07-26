<?php

namespace App\Filament\Resources\SubscriptionPlans\Tables;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionTier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')
                    ->label('Plan')
                    ->searchable(),
                TextColumn::make('tier')
                    ->badge(),
                TextColumn::make('gateway')
                    ->badge(),
                TextColumn::make('interval'),
                TextColumn::make('amount')
                    ->formatStateUsing(fn (int $state, $record) => number_format($state / 100, 2).' '.strtoupper($record->currency)),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tier')
            ->filters([
                SelectFilter::make('tier')
                    ->options(SubscriptionTier::class),
                SelectFilter::make('gateway')
                    ->options(PaymentGateway::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
