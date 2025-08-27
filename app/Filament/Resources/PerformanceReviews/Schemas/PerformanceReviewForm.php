<?php

namespace App\Filament\Resources\PerformanceReviews\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Placeholder;
use Closure;
USE App\Models\Employee;
use App\Models\User;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\HtmlString;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Penilaian')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Penilaian')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Select::make('employee_id')
                            ->label('Karyawan yang Dinilai')
                            ->options(
                                Employee::with('user')->get()->pluck('user.name', 'id')
                            )
                            ->searchable()
                            ->required(),

                        Select::make('reviewer_id')
                            ->label('Penilai (Reviewer)')
                            // PERBAIKAN: Memastikan tidak ada nama user yang null
                            ->options(User::whereNotNull('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        DatePicker::make('period_start_date')
                            ->label('Tanggal Mulai Periode')
                            ->required(),
                        
                        DatePicker::make('period_end_date')
                            ->label('Tanggal Selesai Periode')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'in_progress' => 'Sedang Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('draft'),
                    ])
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 1,
                    ])
                    ->columnSpanFull()
                    ,

                Section::make('Key Performance Indicators (KPI)')
                    ->schema([
                        Repeater::make('kpis')
                            ->relationship()
                            ->columns(4)
                            ->schema([
                                Textarea::make('description')
                                    ->label('Deskripsi KPI')
                                    ->required()
                                    ->columnSpan(2),
                                
                                TextInput::make('target_metric')
                                    ->label('Satuan')
                                    ->required()
                                    ->placeholder('Contoh: Artikel, %, Rupiah'),

                                TextInput::make('weight')
                                    ->label('Bobot (%)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->required(),

                                TextInput::make('target_value')
                                    ->label('Target')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true),

                                TextInput::make('actual_value')
                                    ->label('Aktual')
                                    ->numeric()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $target = (float) $get('target_value');
                                        $actual = (float) $state;

                                        if ($target > 0) {
                                            $score = ($actual / $target) * 100;
                                            $set('score', round($score, 2)); // pakai round biar angka lebih bersih
                                        } else {
                                            $set('score', 0);
                                        }
                                    }),

                                TextInput::make('score')
                                    ->label('Skor')
                                    ->numeric()
                                    ->readOnly()
                                    ->placeholder('Otomatis terisi'),
                                
                                Textarea::make('manager_comment')
                                    ->label('Komentar Manajer')
                                    ->columnSpanFull(),
                                    ])
                                    ->addActionLabel('Tambah KPI')
                                    ->collapsible()
                                    ->live() // Membuat repeater live untuk validasi
                                    ->rules([
                                        function () {
                                            return function (string $attribute, $value, Closure $fail) {
                                                $totalWeight = collect($value)->sum('weight');

                                                if (count($value) > 0 && $totalWeight != 100) {
                                                    $fail("Total bobot KPI harus tepat 100%. Total saat ini adalah {$totalWeight}%.");
                                                }
                                            };
                                        },
                                    ]),
                                Placeholder::make('total_weight_placeholder')
                                ->label('Total Bobot Saat Ini')
                                ->content(function (Get $get): string {
                                    $kpis = $get('kpis');
                                    if (is_array($kpis)) {
                                        $total = collect($kpis)->sum('weight');
                                        $color = $total == 100 ? 'text-green-600' : 'text-red-600';
                                        return "<span class='font-bold text-lg {$color}'>{$total}%</span>";
                                    }
                                    return '0%';
                                })
                                ->html()

                    ])
                    ->columnSpanFull(),
                
                Section::make('Feedback')
                    ->schema([
                        RichEditor::make('manager_feedback')
                            ->label('Feedback dari Manajer'),
                        RichEditor::make('employee_feedback')
                            ->label('Feedback dari Karyawan'),
                    ])
                    ->columnSpanFull()
                    ,
            ]);
    }
}
