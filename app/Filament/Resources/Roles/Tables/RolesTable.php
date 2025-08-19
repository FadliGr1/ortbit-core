<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label('Nama Peran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d-M-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // [FIX] Memaksa tombol Edit untuk memeriksa policy 'update'
                EditAction::make(),

                // [FIX] Memaksa tombol Delete untuk memeriksa policy 'delete'
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // [FIX] Memaksa aksi hapus massal untuk memeriksa policy 'deleteAny'
                    DeleteBulkAction::make(),
                ]),
            ])
            ;
    }
}
