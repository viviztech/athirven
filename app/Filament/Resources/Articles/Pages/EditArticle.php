<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Tables\ArticlesTable;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected array $authorsToSync = [];

    protected function getHeaderActions(): array
    {
        return [
            ...ArticlesTable::workflowActions(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['authors'] = $this->record->authors->map(fn ($author) => [
            'author_id' => $author->id,
            'role' => $author->pivot->role,
            'sort_order' => $author->pivot->sort_order,
        ])->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->authorsToSync = $data['authors'] ?? [];
        unset($data['authors']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->authors()->sync(
            collect($this->authorsToSync)->mapWithKeys(fn (array $row) => [
                $row['author_id'] => ['role' => $row['role'], 'sort_order' => $row['sort_order'] ?? 0],
            ])->all()
        );
    }
}
