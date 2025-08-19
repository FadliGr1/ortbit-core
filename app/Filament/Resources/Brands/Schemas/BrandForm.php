<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Utama')
                    ->description('Detail dasar untuk setiap brand yang dikelola.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Brand')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('URL Website')
                            ->url()
                            ->maxLength(255),
                        Select::make('locale')
                            ->label('Bahasa')
                            ->options([
                                'id' => 'Indonesia (ID)',
                                'en' => 'English (EN)',
                            ])
                            ->required()
                            ->default('id'),
                        TextInput::make('monetization_type')
                            ->label('Tipe Monetisasi')
                            ->placeholder('Contoh: AdSense, Jasa, Affiliate')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Visual & Status')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo Brand')
                            ->image()
                            ->imageEditor()
                            ->directory('brand-logos')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->required()
                            ->default(true),
                    ]),
            ]);
    }
}
