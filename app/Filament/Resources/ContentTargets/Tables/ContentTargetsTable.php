<?php

namespace App\Filament\Resources\ContentTargets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentTargetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand.name')
                    ->sortable(),
                TextColumn::make('year')
                    ->sortable(),
                TextColumn::make('month')
                    ->formatStateUsing(fn (string $state): string => date('F', mktime(0, 0, 0, $state, 1)))
                    ->sortable(),
                TextColumn::make('target_count')
                    ->label('Target')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
