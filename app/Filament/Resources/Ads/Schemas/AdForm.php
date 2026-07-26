<?php

namespace App\Filament\Resources\Ads\Schemas;

use App\Enums\AdPlacement;
use App\Models\Ad;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sponsor')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sponsor_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('target_url')
                            ->label('Target URL')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('creative')
                            ->collection('creative')
                            ->image()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                    ]),

                Section::make('Placement & schedule')
                    ->columns(2)
                    ->schema([
                        Select::make('placement')
                            ->options(AdPlacement::class)
                            ->required(),
                        Placeholder::make('status_display')
                            ->label('Status')
                            ->content(fn (?Ad $record) => $record?->status->getLabel() ?? 'Pending (new)')
                            ->helperText('Status changes via the workflow actions on the list/edit page, not this field.'),
                        Hidden::make('status')->default('pending'),
                        DateTimePicker::make('starts_at'),
                        DateTimePicker::make('ends_at'),
                        TextInput::make('price_paid')
                            ->numeric()
                            ->helperText('Minor units, informational only — sponsorships are negotiated offline.'),
                        TextInput::make('currency')
                            ->maxLength(3),
                    ]),
            ]);
    }
}
