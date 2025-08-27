<?php

namespace App\Filament\Resources\ContentBriefs\Pages;

use App\Filament\Resources\ContentBriefs\ContentBriefResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentBrief extends EditRecord
{
    protected static string $resource = ContentBriefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
