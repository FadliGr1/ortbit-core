<?php

namespace App\Filament\Resources\Payslips\Tables;

use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Barryvdh\DomPDF\Facade\Pdf;


class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')->label('Karyawan')->searchable()->sortable(),
                TextColumn::make('employee.position')->label('Jabatan')->searchable()->sortable(),
                TextColumn::make('month')->label('Bulan')->sortable(),
                TextColumn::make('year')->label('Tahun')->sortable(),
                TextColumn::make('net_pay')->label('Gaji Bersih')->money('IDR')->sortable(),
                TextColumn::make('pay_date')->label('Tanggal Bayar')->date('d M Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.payslip-pdf', ['payslip' => $record]);
                        $fileName = 'payslip-' . $record->employee->user->name . '-' . $record->month . '-' . $record->year . '.pdf';
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, $fileName);
                    }),
                ]),
                
            ])
            ->bulkActions([]);
    }
}
