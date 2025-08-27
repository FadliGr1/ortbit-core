<?php

namespace App\Filament\Resources\Leaves\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use App\Models\Employee;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Carbon;


class LeaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                        Placeholder::make('leave_quota_info')
                            ->label('Informasi Kuota Cuti')
                            ->content(function ($get) {
                                $employeeId = $get('employee_id');
                                if (!$employeeId) {
                                    // Cek untuk pengguna yang sedang login jika employee_id belum dipilih
                                    $user = auth()->user();
                                    $employee = Employee::where('user_id', $user->id)->first();
                                    if ($employee) {
                                        return 'Sisa kuota cuti Anda: ' . $employee->leave_quota . ' hari';
                                    }
                                    return 'Pilih karyawan untuk melihat kuota cuti.';
                                }
                                
                                $employee = Employee::find($employeeId);
                                return 'Sisa kuota cuti: ' . $employee->leave_quota . ' hari';
                            })
                            // Update konten placeholder saat employee_id berubah
                            ->live(),
                        Select::make('employee_id')
                            ->relationship('employee.user', 'name')
                            ->label('Nama Karyawan')
                            ->default(function () {
                                // Otomatis pilih karyawan berdasarkan user yang login
                                $user = auth()->user();
                                if ($user->hasRole('Super Admin')) {
                                    return null; // Super Admin bisa memilih
                                }
                                $employee = Employee::where('user_id', $user->id)->first();
                                return $employee ? $employee->id : null;
                            })
                            ->disabled(fn() => !auth()->user()->hasRole('Super Admin')) // Nonaktifkan jika bukan Super Admin
                            ->required(),
                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'annual' => 'Cuti Tahunan',
                                'sick' => 'Izin Sakit',
                                'unpaid' => 'Cuti Tidak Dibayar',
                                'other' => 'Lainnya',
                            ])
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            // Validasi agar tidak melebihi kuota
                            ->rules([
                                'after_or_equal:start_date',
                                function ($get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        // Validasi ini hanya berlaku untuk 'Cuti Tahunan'
                                        if ($get('type') !== 'annual') {
                                            return;
                                        }

                                        $startDate = Carbon::parse($get('start_date'));
                                        $endDate = Carbon::parse($value);
                                        // Hitung jumlah hari kerja (Senin-Jumat)
                                        $requestedDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
                                            return !$date->isWeekend();
                                        }, $endDate) + 1;

                                        $employee = Employee::find($get('employee_id'));
                                        if ($employee && $requestedDays > $employee->leave_quota) {
                                            $fail("Durasi cuti ({$requestedDays} hari) melebihi sisa kuota cuti Anda ({$employee->leave_quota} hari).");
                                        }
                                    };
                                },
                            ]),
                        Textarea::make('reason')
                            ->label('Alasan')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('attachment_path')
                            ->label('Lampiran (opsional)')
                            ->directory('leave-attachments')
                            ->visibility('private'),
            ])
            
            ->columns(2);
    }
}
