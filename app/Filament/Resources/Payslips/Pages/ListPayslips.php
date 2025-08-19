<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;   




class ListPayslips extends ListRecords
{
    protected static string $resource = PayslipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Karyawan')
                    ->schema([
                        TextEntry::make('employee.user.name')->label('Nama Karyawan'),
                        TextEntry::make('employee.departments.name')
                        ->label('Departments')
                        ->badge(),
                        TextEntry::make('employee.position')->label('Jabatan'),
                        TextEntry::make('month')->label('Bulan Gaji'),
                        TextEntry::make('employee.nik')->label('NIK'),
                        TextEntry::make('year')->label('Tahun Gaji'),
                        TextEntry::make('pay_date')->label('Tanggal Pembayaran')->date('d F Y'),
                        TextEntry::make('employee.bank_account_details')->label('Bank'),
                    ])
                    ->columnSpanFull()
                    ->columns(3),

                Section::make('Rincian Gaji')
                    ->schema([
                        KeyValue::make('earnings')
                            ->label('Pendapatan')
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state): array {
                                $formatted = [];
                                foreach ($state as $key => $value) {
                                    // Format setiap nilai menjadi format mata uang IDR
                                    $formatted[$key] = 'IDR ' . number_format($value, 0, ',', '.');
                                }
                                return $formatted;
                            }),
                        
                        KeyValue::make('deductions')
                            ->label('Potongan')
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state): array {
                                $formatted = [];
                                foreach ($state as $key => $value) {
                                    // Format setiap nilai menjadi format mata uang IDR
                                    $formatted[$key] = 'IDR ' . number_format($value, 0, ',', '.');
                                }
                                return $formatted;
                            }),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make('Ringkasan')
                    ->schema([
                        TextEntry::make('total_earnings')->label('Total Pendapatan')->money('IDR'),
                        TextEntry::make('total_deductions')->label('Total Potongan')->money('IDR'),
                        TextEntry::make('net_pay')->label('Gaji Bersih (Take-Home Pay)')->money('IDR')->weight('bold')->size('lg'),
                    ])
                    ->columnSpanFull()
                    ->columns(3),
            ]);
    }
}
