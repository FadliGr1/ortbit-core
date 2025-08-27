<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use App\Models\Payslip;
use App\Models\Payroll;
use Filament\Notifications\Notification;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')->label('Karyawan')->searchable()->sortable(),
                TextColumn::make('base_salary')->label('Gaji Pokok')->money('IDR')->sortable(),
                TextColumn::make('effective_date')->label('Tanggal Berlaku')->date('d M Y')->sortable(),
                TextColumn::make('transport_allowance')->label('Tunjangan Transportasi')->money('IDR')->sortable(),
                TextColumn::make('meal_allowance')->label('Tunjangan Makan')->money('IDR')->sortable(),
                TextColumn::make('bpjs_health_deduction')->label('Potongan BPJS Kesehatan')->money('IDR')->sortable(),
                TextColumn::make('bpjs_employment_deduction')->label('Potongan BPJS Ketenagakerjaan')->money('IDR')->sortable(),
                TextColumn::make('tax_deduction')->label('Potongan Pajak (PPh 21)')->money('IDR')->sortable(),  
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make(),
                    Action::make('generatePayslip')
                            ->label('Generate Payslip')
                            ->icon('heroicon-o-document-plus')
                            ->form(function (Payroll $record) {
                                return [
                                    Section::make('Periode Gaji')->schema([
                                        Select::make('month')->label('Bulan')->options(array_combine(range(1, 12), range(1, 12)))->default(date('n'))->required(),
                                        TextInput::make('year')->label('Tahun')->numeric()->default(date('Y'))->required(),
                                        DatePicker::make('pay_date')->label('Tanggal Pembayaran')->default(now())->required(),
                                    ])->columns(3),
                                    Section::make('Pendapatan Variabel (Bonus, Lembur, dll)')->schema([
                                        KeyValue::make('variable_earnings')->label('')->keyLabel('Nama Pendapatan')->valueLabel('Jumlah (Rp)')->reorderable()->default($record->other_allowances),
                                    ]),
                                    Section::make('Potongan Variabel (Insidental)')->schema([
                                        KeyValue::make('variable_deductions')->label('')->keyLabel('Nama Potongan')->valueLabel('Jumlah (Rp)')->reorderable()->default($record->other_deductions),
                                    ]),
                                    Textarea::make('notes')->label('Catatan'),
                                ];
                            })
                            ->action(function (Payroll $record, array $data) {
                                // Cek apakah slip gaji untuk periode ini sudah ada
                                $existingPayslip = Payslip::where('employee_id', $record->employee_id)
                                    ->where('month', $data['month'])
                                    ->where('year', $data['year'])
                                    ->exists();

                                if ($existingPayslip) {
                                    Notification::make()->title('Gagal! Slip gaji untuk periode ini sudah pernah dibuat.')->danger()->send();
                                    return;
                                }

                                // Kumpulkan semua pendapatan
                                $earnings = [
                                    'Gaji Pokok' => $record->base_salary,
                                    'Tunjangan Transportasi' => $record->transport_allowance,
                                    'Tunjangan Makan' => $record->meal_allowance,
                                ];
                                foreach ($data['variable_earnings'] as $key => $value) {
                                    $earnings[$key] = $value;
                                }

                                // Kumpulkan semua potongan
                                $deductions = [
                                    'Potongan BPJS Kesehatan' => $record->bpjs_health_deduction,
                                    'Potongan BPJS Ketenagakerjaan' => $record->bpjs_empzloyment_deduction,
                                    'Potongan Pajak (PPh 21)' => $record->tax_deduction,
                                    'Potongan Pinjaman' => $record->loan_deduction,
                                ];
                                foreach ($data['variable_deductions'] as $key => $value) {
                                    $deductions[$key] = $value;
                                }

                                // Hitung total
                                $totalEarnings = array_sum($earnings);
                                $totalDeductions = array_sum($deductions);
                                $netPay = $totalEarnings - $totalDeductions;

                                // Buat record payslip baru
                                Payslip::create([
                                    'employee_id' => $record->employee_id,
                                    'month' => $data['month'],
                                    'year' => $data['year'],
                                    'pay_date' => $data['pay_date'],
                                    'earnings' => $earnings,
                                    'deductions' => $deductions,
                                    'total_earnings' => $totalEarnings,
                                    'total_deductions' => $totalDeductions,
                                    'net_pay' => $netPay,
                                    'notes' => $data['notes'],
                                ]);

                                Notification::make()->title('Slip gaji berhasil dibuat.')->success()->send();
                    }),
                ]),
                
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportAction::make(),
                ]),
            ]);
    }
}
