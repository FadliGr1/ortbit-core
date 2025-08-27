<?php

namespace App\Filament\Resources\ContentBriefs;

use App\Filament\Resources\ContentBriefs\Pages\CreateContentBrief;
use App\Filament\Resources\ContentBriefs\Pages\EditContentBrief;
use App\Filament\Resources\ContentBriefs\Pages\ListContentBriefs;
use App\Filament\Resources\ContentBriefs\Schemas\ContentBriefForm;
use App\Filament\Resources\ContentBriefs\Tables\ContentBriefsTable;
use App\Models\ContentBrief;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentBriefResource extends Resource
{
    protected static ?string $model = ContentBrief::class;
    protected static ?string $recordTitleAttribute = 'brief';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string|\UnitEnum|null $navigationGroup = 'Task Force';
    protected static ?string $navigationLabel = 'Content Briefs';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ContentBriefForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentBriefsTable::configure($table);
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
            'index' => ListContentBriefs::route('/'),
            'create' => CreateContentBrief::route('/create'),
            'edit' => EditContentBrief::route('/{record}/edit'),
        ];
    }
}
