<?php

namespace App\Filament\Resources\SubscriptionPlans\Schemas;

use App\Enums\PaymentGateway;
use App\Enums\SubscriptionTier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Slug used internally, e.g. digital-monthly-stripe.'),
                        Select::make('tier')
                            ->options(SubscriptionTier::class)
                            ->required(),
                        TextInput::make('name_ta')
                            ->label('பெயர் (Tamil)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_en')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255),
                        Select::make('gateway')
                            ->options(PaymentGateway::class)
                            ->required()
                            ->helperText('Which gateway this specific plan row checks out through.'),
                        Select::make('interval')
                            ->options(['month' => 'Monthly', 'year' => 'Yearly'])
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),

                Section::make('Pricing & gateway IDs')
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->helperText('Minor units — cents for USD, paise for INR (e.g. 500 = $5.00).'),
                        TextInput::make('currency')
                            ->required()
                            ->maxLength(3)
                            ->helperText('3-letter ISO code, e.g. usd or inr.'),
                        TextInput::make('stripe_price_id')
                            ->label('Stripe Price ID')
                            ->helperText('From the Stripe Dashboard, once this plan is created there.'),
                        TextInput::make('razorpay_plan_id')
                            ->label('Razorpay Plan ID')
                            ->helperText('From the Razorpay Dashboard, once this plan is created there.'),
                    ]),
            ]);
    }
}
