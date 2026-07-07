<?php

namespace App\Filament\Resources\Issues\RelationManagers;

use App\Enums\ArticleStatus;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * The "issue builder": assembling approved articles into a monthly issue,
 * ordering them, and detaching pieces that get pulled at the last minute.
 * Creating brand-new articles happens on ArticleResource, not here — this
 * only attaches/orders articles that already exist.
 */
class ArticlesRelationManager extends RelationManager
{
    protected static string $relationship = 'articles';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('authors.pen_name')
                    ->label('Authors')
                    ->badge(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                AssociateAction::make()
                    ->label('Add existing article')
                    ->recordSelectOptionsQuery(
                        fn ($query) => $query->whereIn('status', [
                            ArticleStatus::Approved->value,
                            ArticleStatus::Scheduled->value,
                        ])
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make()
                    ->label('Remove from issue'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}
