<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->relationship('parent', 'name_ta')
                    ->searchable()
                    ->preload()
                    ->label('Parent category'),
                TextInput::make('name_ta')
                    ->label('பெயர் (Tamil)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_en')
                    ->label('Name (English)')
                    ->maxLength(255),
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Leave blank to auto-generate from the Tamil name.'),
            ]);
    }
}
