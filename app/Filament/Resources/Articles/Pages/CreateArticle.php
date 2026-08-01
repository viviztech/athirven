<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected array $authorsToSync = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->authorsToSync = $data['authors'] ?? [];
        unset($data['authors']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->authors()->sync(
            collect($this->authorsToSync)->mapWithKeys(fn (array $row) => [
                $row['author_id'] => ['role' => $row['role'], 'sort_order' => $row['sort_order'] ?? 0],
            ])->all()
        );
    }
}
