<?php

namespace App\Filament\Resources\ContentBriefs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use App\Models\Brand;
use Filament\Schemas\Schema;

class ContentBriefForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('keyword_primary')
                            ->label('Keyword Utama')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->options(Brand::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('status')
                            ->options([
                                'brief' => 'Brief',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->required()
                            ->default('brief'),

                        TextInput::make('intent')
                            ->label('Search Intent')
                            ->maxLength(255),

                        TextInput::make('angle')
                            ->label('Content Angle')
                            ->maxLength(255),
                        
                        TextInput::make('wordcount_goal')
                            ->label('Target Jumlah Kata')
                            ->numeric()
                            ->default(1000),
                        
                        DatePicker::make('due_date')
                            ->label('Batas Waktu'),
                        
                        MarkdownEditor::make('outline_md')
                            ->label('Outline (Markdown)')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
