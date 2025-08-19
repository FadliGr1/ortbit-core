<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Karyawan')
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee.user', 'name')
                            ->label('Karyawan')
                            ->required(),
                        DatePicker::make('effective_date')
                            ->label('Tanggal Berlaku')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Komponen Pendapatan')
                    ->schema([
                        TextInput::make('base_salary')->label('Gaji Pokok')->numeric()->prefix('Rp')->required(),
                        TextInput::make('transport_allowance')->label('Tunjangan Transportasi')->numeric()->prefix('Rp')->required(),
                        TextInput::make('meal_allowance')->label('Tunjangan Makan')->numeric()->prefix('Rp')->required(),
                        KeyValue::make('other_allowances')
                            ->label('Tunjangan Lainnya')
                            ->keyLabel('Nama Tunjangan')
                            ->valueLabel('Jumlah (Rp)')
                            ->reorderable(),
                    ])->columns(1),

                Section::make('Komponen Potongan')
                    ->schema([
                        TextInput::make('bpjs_health_deduction')->label('Potongan BPJS Kesehatan')->numeric()->prefix('Rp')->required(),
                        TextInput::make('bpjs_employment_deduction')->label('Potongan BPJS Ketenagakerjaan')->numeric()->prefix('Rp')->required(),
                        TextInput::make('tax_deduction')->label('Potongan Pajak (PPh 21)')->numeric()->prefix('Rp')->required(),
                        TextInput::make('loan_deduction')->label('Potongan Pinjaman')->numeric()->prefix('Rp')->required(),
                        KeyValue::make('other_deductions')
                            ->label('Potongan Lainnya')
                            ->keyLabel('Nama Potongan')
                            ->valueLabel('Jumlah (Rp)')
                            ->reorderable(),
                    ])
                    // ->compact()
                    ->columns(1),
            ]);
    }
}
