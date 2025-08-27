<?php

namespace App\Filament\Resources\ContentTargets\Pages;

use App\Filament\Resources\ContentTargets\ContentTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentTargets extends ListRecords
{
    protected static string $resource = ContentTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
