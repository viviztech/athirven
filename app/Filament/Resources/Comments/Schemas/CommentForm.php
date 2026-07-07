<?php

namespace App\Filament\Resources\Comments\Schemas;

use App\Enums\CommentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('article_id')
                    ->relationship('article', 'title')
                    ->disabled(),
                TextInput::make('author_display_name')
                    ->label('Commenter')
                    ->disabled(),
                Textarea::make('body')
                    ->disabled()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(CommentStatus::class)
                    ->required(),
                Select::make('moderated_by_id')
                    ->label('Moderated by')
                    ->relationship('moderatedBy', 'name')
                    ->disabled(),
                DateTimePicker::make('moderated_at')
                    ->disabled(),
            ]);
    }
}
