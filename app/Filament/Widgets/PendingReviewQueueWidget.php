<?php

namespace App\Filament\Widgets;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingReviewQueueWidget extends TableWidget
{
    protected static ?string $heading = 'Review queue';

    public static function canView(): bool
    {
        return auth()->user()?->can('articles.review') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Article::query()
                ->whereIn('status', [ArticleStatus::Submitted, ArticleStatus::InReview])
                ->orderBy('submitted_at'))
            ->columns([
                TextColumn::make('title')
                    ->limit(50),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('createdBy.name')
                    ->label('Writer'),
                TextColumn::make('submitted_at')
                    ->label('Days pending')
                    ->state(fn (Article $record) => $record->submitted_at ? (int) $record->submitted_at->diffInDays(now()) : 0)
                    ->color(fn (int $state) => match (true) {
                        $state >= 3 => 'danger',
                        $state >= 1 => 'warning',
                        default => 'success',
                    })
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn (Article $record) => route('filament.admin.resources.articles.edit', $record)),
            ])
            ->paginated(false);
    }
}
