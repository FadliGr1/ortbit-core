<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Filament\Resources\Leaves\Schemas\EmployeeLeaveRequestForm; 
use Filament\Schemas\Schema;

class OrbitPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('orbit')
            ->path('orbit')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                Action::make('Clock In/Out')
                    ->label(function (): string {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        if (!$employee) return 'Absensi (Non-Karyawan)';

                        $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first();

                        if (!$todayAttendance) {
                            return 'Clock In';
                        } elseif ($todayAttendance->check_in_at && !$todayAttendance->check_out_at) {
                            return 'Clock Out';
                        } else {
                            return 'Absensi Selesai';
                        }
                    })
                    ->icon('heroicon-o-clock')
                    ->color(function (): string {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        if (!$employee) return 'gray';
                        $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first();
                        return !$todayAttendance || !$todayAttendance->check_out_at ? 'primary' : 'success';
                    })
                    ->disabled(function (): bool {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        if (!$employee) return true; // Nonaktifkan jika bukan karyawan
                        $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first();
                        return $todayAttendance && $todayAttendance->check_out_at; // Nonaktifkan jika sudah clock out
                    })
                    ->action(function () {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        $now = Carbon::now();
                        $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first();

                        if (!$todayAttendance) {
                            // Proses Clock In
                            Attendance::create([
                                'employee_id' => $employee->id,
                                'date' => $now->toDateString(),
                                'check_in_at' => $now->toTimeString(),
                                'status' => 'present',
                            ]);
                            Notification::make()->title('Berhasil Clock In pada jam ' . $now->format('H:i'))->success()->send();
                        } elseif ($todayAttendance->check_in_at && !$todayAttendance->check_out_at) {
                            // Proses Clock Out
                            $todayAttendance->update([
                                'check_out_at' => $now->toTimeString(),
                            ]);
                            Notification::make()->title('Berhasil Clock Out pada jam ' . $now->format('H:i'))->success()->send();
                        }
                    }),

                Action::make('requestLeave')
                    ->label('Ajukan Cuti / Izin')
                    ->icon('heroicon-o-document-plus')
                    // Tombol hanya terlihat jika user adalah seorang karyawan
                    ->visible(fn (): bool => Employee::where('user_id', auth()->id())->exists())
                    // Tombol dinonaktifkan jika kuota cuti habis
                    ->disabled(function () {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        // Nonaktifkan jika data karyawan tidak ada atau kuota <= 0
                        if (!$employee || $employee->leave_quota <= 0) {
                            return true;
                        }
                        return false;
                    })
                    // Tampilkan pesan saat tombol dinonaktifkan
                    ->tooltip(function () {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        if ($employee && $employee->leave_quota <= 0) {
                            return 'Kuota cuti Anda sudah habis.';
                        }
                        return null;
                    })
                    ->form(fn (Schema $schema) => EmployeeLeaveRequestForm::configure($schema))
                    ->action(function (array $data) {
                        $employee = Employee::where('user_id', auth()->id())->first();
                        // Tambahkan pengecekan sekali lagi sebelum membuat data, untuk keamanan
                        if (!$employee || $employee->leave_quota <= 0) {
                            Notification::make()->title('Gagal! Kuota cuti Anda tidak mencukupi.')->danger()->send();
                            return;
                        }

                        Leave::create([
                            'employee_id' => $employee->id,
                            'type' => $data['type'],
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                            'reason' => $data['reason'],
                            'attachment_path' => $data['attachment_path'] ?? null,
                            'status' => 'pending',
                        ]);
                        Notification::make()->title('Pengajuan cuti/izin berhasil dikirim.')->success()->send();
                    })

            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->topNavigation()
            
           
            ;
    }
}
