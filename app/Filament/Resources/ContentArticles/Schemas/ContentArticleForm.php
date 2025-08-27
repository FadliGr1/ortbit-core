<?php

namespace App\Filament\Resources\ContentArticles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\ContentBrief;
use App\Models\User;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;

class ContentArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Section::make('Konten Utama')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Artikel')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->maxLength(255),
                                
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                RichEditor::make('body')
                                    ->label('Isi Artikel')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Section::make()
                    ->schema([
                        Section::make('Metadata & Status')
                            ->schema([
                                Select::make('brand_id')
                                    ->label('Brand')
                                    ->options(Brand::all()->pluck('name', 'id'))
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'seo_check' => 'SEO Check',
                                        'review' => 'Review',
                                        'ready' => 'Ready',
                                        'published' => 'Published',
                                        'refresh_due' => 'Refresh Due',
                                        'updated' => 'Updated',
                                    ])
                                    ->required()
                                    ->default('draft'),

                                Select::make('brief_id')
                                    ->label('Terhubung ke Brief')
                                    ->options(ContentBrief::where('status', '!=', 'completed')->pluck('keyword_primary', 'id'))
                                    ->searchable(),

                                Select::make('author_id')
                                    ->label('Penulis (Author)')
                                    ->options(User::all()->pluck('name', 'id')) // Nanti bisa difilter hanya untuk role writer
                                    ->searchable()
                                    ->required(),

                                Select::make('editor_id')
                                    ->label('Editor')
                                    ->options(User::all()->pluck('name', 'id')) // Nanti bisa difilter hanya untuk role editor
                                    ->searchable(),

                                DateTimePicker::make('publish_date')
                                    ->label('Tanggal Publikasi'),
                            ]),
                        
                        Section::make('Checklist SEO')
                            ->schema([
                                CheckboxList::make('seo_checklist')
                                    ->label('')
                                    ->options([
                                        'meta_title' => 'Meta Title Sesuai',
                                        'meta_desc' => 'Meta Description Sesuai',
                                        'internal_link' => 'Ada Internal Link',
                                        'external_link' => 'Ada External Link',
                                        'image_alt' => 'Semua Gambar punya ALT Text',
                                    ])
                            ])
                    ])->columnSpan(1),
            ])->columns(3);
    }
}
