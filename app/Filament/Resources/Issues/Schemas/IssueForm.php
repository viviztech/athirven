<?php

namespace App\Filament\Resources\Issues\Schemas;

use App\Enums\IssueStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class IssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('issue_number')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Leave blank to auto-generate from the title.'),
                Select::make('month')
                    ->options([
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
                    ])
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->numeric()
                    ->minValue(2026)
                    ->default(now()->year),
                DatePicker::make('publish_date'),
                Select::make('status')
                    ->options(IssueStatus::class)
                    ->default(IssueStatus::Draft)
                    ->required(),
                Toggle::make('is_premium')
                    ->label('Print + digital (premium) issue'),
                SpatieMediaLibraryFileUpload::make('cover_image')
                    ->collection('cover_image')
                    ->image(),
                SpatieMediaLibraryFileUpload::make('issue_pdf')
                    ->collection('issue_pdf')
                    ->acceptedFileTypes(['application/pdf'])
                    ->helperText('Upload the full-issue PDF as laid out externally (InDesign/Canva).'),
            ]);
    }
}
