<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Enums\ArticleAuthorRole;
use App\Enums\ArticleEmbedProvider;
use App\Enums\ArticleEmbedType;
use App\Enums\ArticleType;
use App\Models\Article;
use App\Models\Author;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate from the title.')
                            ->columnSpanFull(),
                        TextInput::make('subtitle')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options(ArticleType::class)
                            ->default(ArticleType::Opinion)
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name_ta')
                            ->searchable()
                            ->preload(),
                        Textarea::make('excerpt')
                            ->rows(2)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->required()
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('featured_image')
                            ->collection('featured_image')
                            ->image()
                            ->columnSpanFull(),
                        Select::make('tags')
                            ->relationship('tags', 'name_ta')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Section::make('Authors')
                    ->schema([
                        Repeater::make('authors')
                            ->relationship('authors')
                            ->schema([
                                Select::make('author_id')
                                    ->label('Author')
                                    ->options(fn () => Author::query()->pluck('pen_name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Select::make('role')
                                    ->options(ArticleAuthorRole::class)
                                    ->default(ArticleAuthorRole::Author)
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Add byline'),
                    ]),

                Section::make('Audio & Video')
                    ->schema([
                        Repeater::make('embeds')
                            ->relationship('embeds')
                            ->label('Embeds')
                            ->schema([
                                Select::make('type')
                                    ->options(ArticleEmbedType::class)
                                    ->default(ArticleEmbedType::Video)
                                    ->required(),
                                Select::make('provider')
                                    ->options(ArticleEmbedProvider::class)
                                    ->default(ArticleEmbedProvider::YouTube)
                                    ->required(),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('caption')
                                    ->columnSpanFull(),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add audio/video embed'),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Select::make('issue_id')
                            ->relationship('issue', 'title')
                            ->searchable()
                            ->preload(),
                        Placeholder::make('status_display')
                            ->label('Status')
                            ->content(fn (?Article $record) => $record?->status->getLabel() ?? 'Draft (new)')
                            ->helperText('Status changes via the workflow actions on the list/edit page, not this field.'),
                        Hidden::make('status')->default('draft'),
                        TextInput::make('reading_time_minutes')
                            ->numeric(),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('published_at')
                            ->disabled(),
                        DateTimePicker::make('scheduled_at'),
                        Toggle::make('is_premium')
                            ->label('Premium (subscriber-only) content'),
                        Toggle::make('allow_comments')
                            ->default(true),
                        Select::make('comment_moderation_mode')
                            ->options(['pre' => 'Pre-moderate', 'post' => 'Post-moderate'])
                            ->default('pre')
                            ->required(),
                    ]),

                Section::make('Editorial feedback')
                    ->columns(2)
                    ->visible(fn (?Article $record) => filled($record?->revision_notes) || filled($record?->proofreader_notes))
                    ->schema([
                        Textarea::make('revision_notes')
                            ->label('Revision notes (from reviewer)')
                            ->disabled()
                            ->visible(fn (?Article $record) => filled($record?->revision_notes)),
                        Textarea::make('proofreader_notes')
                            ->label('Proofreader notes')
                            ->disabled()
                            ->visible(fn (?Article $record) => filled($record?->proofreader_notes)),
                    ]),

                Section::make('SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(255),
                        TextInput::make('meta_description')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
