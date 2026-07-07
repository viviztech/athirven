<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Enums\CommentStatus;
use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('article.title')
                    ->label('Article')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('author_display_name')
                    ->label('Commenter')
                    ->searchable(),
                TextColumn::make('body')
                    ->limit(80)
                    ->wrap(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('moderatedBy.name')
                    ->label('Moderated by')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('moderated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(CommentStatus::class)
                    ->default(CommentStatus::Pending->value),
            ])
            ->recordActions([
                Action::make('approve')
                    ->color('success')
                    ->visible(fn (Comment $record) => $record->status !== CommentStatus::Approved)
                    ->action(fn (Comment $record) => static::moderate($record, CommentStatus::Approved)),
                Action::make('reject')
                    ->color('danger')
                    ->visible(fn (Comment $record) => $record->status !== CommentStatus::Rejected)
                    ->action(fn (Comment $record) => static::moderate($record, CommentStatus::Rejected)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approveSelected')
                        ->label('Approve selected')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each(fn (Comment $record) => static::moderate($record, CommentStatus::Approved))),
                    BulkAction::make('rejectSelected')
                        ->label('Reject selected')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each(fn (Comment $record) => static::moderate($record, CommentStatus::Rejected))),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function moderate(Comment $record, CommentStatus $status): void
    {
        $record->update([
            'status' => $status,
            'moderated_by_id' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Notification::make()->title("Comment {$status->value}")->success()->send();
    }
}
