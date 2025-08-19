<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use PhpParser\Node\Name\FullyQualified;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Peran')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required()
                            ->unique(ignoreRecord: true), // Nama peran harus unik
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Hak Akses (Permissions)')
                    ->description('Pilih hak akses yang akan diberikan untuk peran ini.')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Daftar Hak Akses')
                            ->relationship('permissions', 'name') // Menghubungkan ke relasi 'permissions' di model Role
                            ->searchable() // Tambahkan fitur pencarian
                            ->columns(3), // Tampilkan dalam 3 kolom agar rapi
                    ])

                    ->columnSpanFull()
                    ->columns(1),
            ]);
    }
}
