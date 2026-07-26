<?php

namespace App\Filament\Resources\Donations\Tables;

use App\Enums\DonationStatus;
use App\Enums\PaymentGateway;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('donor_name')
                    ->label('Donor')
                    ->formatStateUsing(fn (?string $state, $record) => $record->is_anonymous ? 'Anonymous' : ($state ?? '—'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn (int $state, $record) => number_format($state / 100, 2).' '.strtoupper($record->currency))
                    ->sortable(),
                TextColumn::make('gateway')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('is_recurring')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(DonationStatus::class),
                SelectFilter::make('gateway')
                    ->options(PaymentGateway::class),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
