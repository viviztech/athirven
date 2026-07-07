<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Enums\ArticleStatus;
use App\Enums\ArticleType;
use App\Models\Article;
use App\Services\ArticleWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('issue.title')
                    ->label('Issue')
                    ->searchable(),
                TextColumn::make('category.name_ta')
                    ->label('Category')
                    ->searchable(),
                TextColumn::make('authors.pen_name')
                    ->label('Authors')
                    ->badge(),
                IconColumn::make('is_premium')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(ArticleStatus::class),
                SelectFilter::make('type')
                    ->options(ArticleType::class),
            ])
            ->recordActions([
                EditAction::make(),
                ...static::workflowActions(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<int, Action>
     */
    public static function workflowActions(): array
    {
        return [
            Action::make('submit')
                ->label('Submit for review')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::Submitted))
                ->action(function (Article $record) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::Submitted, auth()->user());
                    Notification::make()->title('Submitted for review')->success()->send();
                }),

            Action::make('startReview')
                ->label('Start review')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::InReview))
                ->action(function (Article $record) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::InReview, auth()->user());
                    Notification::make()->title('Marked in review')->success()->send();
                }),

            Action::make('markProofread')
                ->label('Mark proofread')
                ->color('gray')
                ->visible(fn (Article $record) => $record->status === ArticleStatus::InReview && (auth()->user()?->can('articles.review') ?? false))
                ->form([
                    Textarea::make('notes')->label('Proofreader notes'),
                ])
                ->action(function (Article $record, array $data) {
                    app(ArticleWorkflowService::class)->markProofread($record, auth()->user(), $data['notes'] ?? null);
                    Notification::make()->title('Proofreading recorded')->success()->send();
                }),

            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::Approved))
                ->action(function (Article $record) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::Approved, auth()->user());
                    Notification::make()->title('Approved')->success()->send();
                }),

            Action::make('needsRevision')
                ->label('Send back for revision')
                ->color('warning')
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::NeedsRevision))
                ->form([
                    Textarea::make('revision_notes')->label('What needs to change?')->required(),
                ])
                ->action(function (Article $record, array $data) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::NeedsRevision, auth()->user(), [
                        'revision_notes' => $data['revision_notes'],
                    ]);
                    Notification::make()->title('Sent back for revision')->warning()->send();
                }),

            Action::make('schedule')
                ->label('Schedule')
                ->color('info')
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::Scheduled))
                ->form([
                    Select::make('issue_id')
                        ->label('Issue')
                        ->relationship('issue', 'title')
                        ->required()
                        ->default(fn (Article $record) => $record->issue_id),
                    DateTimePicker::make('scheduled_at')
                        ->default(now()),
                ])
                ->action(function (Article $record, array $data) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::Scheduled, auth()->user(), $data);
                    Notification::make()->title('Scheduled')->success()->send();
                }),

            Action::make('publish')
                ->label('Publish now')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::Published))
                ->action(function (Article $record) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::Published, auth()->user());
                    Notification::make()->title('Published')->success()->send();
                }),

            Action::make('archive')
                ->label('Archive')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Article $record) => static::can($record, ArticleStatus::Archived))
                ->action(function (Article $record) {
                    app(ArticleWorkflowService::class)->transition($record, ArticleStatus::Archived, auth()->user());
                    Notification::make()->title('Archived')->success()->send();
                }),
        ];
    }

    private static function can(Article $record, ArticleStatus $to): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return in_array($to, app(ArticleWorkflowService::class)->availableTransitions($record, $user), true);
    }
}
