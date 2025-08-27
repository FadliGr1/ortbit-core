<?php

namespace App\Filament\Resources\ContentArticles;

use App\Filament\Resources\ContentArticles\Pages\CreateContentArticle;
use App\Filament\Resources\ContentArticles\Pages\EditContentArticle;
use App\Filament\Resources\ContentArticles\Pages\ListContentArticles;
use App\Filament\Resources\ContentArticles\Schemas\ContentArticleForm;
use App\Filament\Resources\ContentArticles\Tables\ContentArticlesTable;
use App\Models\ContentArticle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentArticleResource extends Resource
{
    protected static ?string $model = ContentArticle::class;
    protected static ?string $recordTitleAttribute = 'article';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string|\UnitEnum|null $navigationGroup = 'Task Force';
    protected static ?string $navigationLabel = 'Content Article';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ContentArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentArticlesTable::configure($table);
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
            'index' => ListContentArticles::route('/'),
            'create' => CreateContentArticle::route('/create'),
            'edit' => EditContentArticle::route('/{record}/edit'),
        ];
    }
}
