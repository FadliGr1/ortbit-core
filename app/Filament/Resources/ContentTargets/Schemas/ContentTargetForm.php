<?php

namespace App\Filament\Resources\ContentTargets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Brand;

class ContentTargetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->label('Brand')
                    ->options(Brand::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('year')
                    ->label('Tahun')
                    ->options(array_combine(range(date('Y'), date('Y') + 5), range(date('Y'), date('Y') + 5)))
                    ->required(),
                Select::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ])
                    ->required(),
                TextInput::make('target_count')
                    ->label('Jumlah Target Artikel')
                    ->numeric()
                    ->required(),
            ]);
    }
}
