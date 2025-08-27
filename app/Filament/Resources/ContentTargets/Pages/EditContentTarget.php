<?php

namespace App\Filament\Resources\ContentTargets\Pages;

use App\Filament\Resources\ContentTargets\ContentTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentTarget extends EditRecord
{
    protected static string $resource = ContentTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
