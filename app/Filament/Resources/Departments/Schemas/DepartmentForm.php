<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255),
                Select::make('parent_id')
                    ->label('Departemen Induk')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->placeholder('Pilih departemen induk jika ada'),
            ]);
    }
}
