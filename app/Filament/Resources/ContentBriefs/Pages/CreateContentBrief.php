<?php

namespace App\Filament\Resources\ContentBriefs\Pages;

use App\Filament\Resources\ContentBriefs\ContentBriefResource;
use Filament\Resources\Pages\CreateRecord;


class CreateContentBrief extends CreateRecord
{
    protected static string $resource = ContentBriefResource::class;

    /**
     * Memodifikasi data formulir sebelum disimpan ke database.
     * Fungsi ini akan menambahkan ID user yang login ke 'created_by'.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
 
        return $data;
    }
}


