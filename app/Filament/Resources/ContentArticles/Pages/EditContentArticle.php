<?php

namespace App\Filament\Resources\ContentArticles\Pages;

use App\Filament\Resources\ContentArticles\ContentArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentArticle extends EditRecord
{
    protected static string $resource = ContentArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
