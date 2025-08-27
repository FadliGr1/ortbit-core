<?php

namespace App\Filament\Resources\ContentArticles\Pages;

use App\Filament\Resources\ContentArticles\ContentArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentArticle extends CreateRecord
{
    protected static string $resource = ContentArticleResource::class;
}
