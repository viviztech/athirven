<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Enums\SubscriptionStatus;
use App\Enums\SubscriptionTier;
use App\Models\MagazineSubscription;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Subscriber')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Select::make('subscription_plan_id')
                            ->label('Plan')
                            ->relationship('plan', 'name_en')
                            ->disabled(),
                        Select::make('status')
                            ->options(SubscriptionStatus::class)
                            ->required()
                            ->helperText('Manual override — use the Cancel action for a normal cancellation so the gateway is notified too.'),
                        DateTimePicker::make('current_period_ends_at'),
                    ]),

                Section::make('Print + Digital shipping address')
                    ->columns(2)
                    ->visible(fn (?MagazineSubscription $record) => $record?->plan?->tier === SubscriptionTier::PrintDigital)
                    ->schema([
                        TextInput::make('shipping_name')->maxLength(255),
                        TextInput::make('shipping_phone')->tel()->maxLength(255),
                        TextInput::make('shipping_line1')->label('Address line 1')->maxLength(255)->columnSpanFull(),
                        TextInput::make('shipping_line2')->label('Address line 2')->maxLength(255)->columnSpanFull(),
                        TextInput::make('shipping_city')->maxLength(255),
                        TextInput::make('shipping_state')->maxLength(255),
                        TextInput::make('shipping_postal_code')->maxLength(255),
                        TextInput::make('shipping_country')->maxLength(255),
                    ]),
            ]);
    }
}
