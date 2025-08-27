<?php

namespace App\Filament\Resources\PerformanceReviews\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\View;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PerformanceReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('employee.user.name')->label('Karyawan')->searchable(),
                TextColumn::make('reviewer.name')->label('Penilai'),
                TextColumn::make('final_score')->label('Final Score'),
                TextColumn::make('status')->badge(),
                TextColumn::make('period_end_date')->label('Akhir Periode')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}
