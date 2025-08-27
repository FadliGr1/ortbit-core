<?php

namespace App\Filament\Resources\ContentTargets;

use App\Filament\Resources\ContentTargets\Pages\CreateContentTarget;
use App\Filament\Resources\ContentTargets\Pages\EditContentTarget;
use App\Filament\Resources\ContentTargets\Pages\ListContentTargets;
use App\Filament\Resources\ContentTargets\Schemas\ContentTargetForm;
use App\Filament\Resources\ContentTargets\Tables\ContentTargetsTable;
use App\Models\ContentTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentTargetResource extends Resource
{
    protected static ?string $model = ContentTarget::class;
    protected static ?string $recordTitleAttribute = 'target';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string|\UnitEnum|null $navigationGroup = 'Task Force';
    protected static ?string $navigationLabel = 'Content Targets';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ContentTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentTargetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentTargets::route('/'),
            // 'create' => CreateContentTarget::route('/create'),
            // 'edit' => EditContentTarget::route('/{record}/edit'),
        ];
    }
}
