<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Linked login (optional)')
                    ->helperText('Only needed if this contributor also has an editorial panel login.'),

                TextInput::make('pen_name')
                    ->label('Byline / pen name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Leave blank to auto-generate from the pen name.'),

                Textarea::make('bio')
                    ->columnSpanFull()
                    ->rows(3),

                SpatieMediaLibraryFileUpload::make('photo')
                    ->collection('photo')
                    ->image()
                    ->avatar(),

                KeyValue::make('social_links')
                    ->keyLabel('Platform')
                    ->valueLabel('URL')
                    ->addButtonLabel('Add link')
                    ->columnSpanFull(),

                Toggle::make('is_pseudonymous')
                    ->label('Writes under a pseudonym')
                    ->helperText('When on, the real identity fields below are hidden from anyone without the authors.view-real-identity permission.')
                    ->live(),

                Section::make('Real identity (protected)')
                    ->description('Visible only to users with the authors.view-real-identity permission.')
                    ->visible(fn () => auth()->user()?->can('authors.view-real-identity'))
                    ->schema([
                        TextInput::make('real_name')
                            ->maxLength(255),
                        TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
