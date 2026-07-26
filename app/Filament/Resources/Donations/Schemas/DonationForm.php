<?php

namespace App\Filament\Resources\Donations\Schemas;

use App\Enums\DonationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Donation')
                    ->columns(2)
                    ->schema([
                        TextInput::make('donor_name')->maxLength(255)->disabled(),
                        TextInput::make('donor_email')->email()->maxLength(255)->disabled(),
                        TextInput::make('amount')
                            ->numeric()
                            ->disabled()
                            ->helperText('Minor units — cents/paise.'),
                        TextInput::make('currency')->maxLength(3)->disabled(),
                        TextInput::make('gateway_reference_id')->disabled(),
                        Select::make('status')
                            ->options(DonationStatus::class)
                            ->required(),
                        Toggle::make('is_anonymous')
                            ->label('Anonymous (hide donor name publicly)')
                            ->disabled(),
                        Toggle::make('is_recurring')
                            ->disabled(),
                    ]),
            ]);
    }
}
