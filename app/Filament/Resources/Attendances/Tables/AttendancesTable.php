<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use App\Models\Attendance;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;


class AttendancesTable
{
    public static function configure(Table $table): Table

    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('check_in_at')
                    ->label('Jam Masuk')
                    ->time('H:i'),
                TextColumn::make('check_out_at')
                    ->label('Jam Pulang')
                    ->time('H:i'),
                TextColumn::make('adjustment_status')
                    ->label('Status Penyesuaian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Normal',
                    }),
            ])
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee.user', 'name'),
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('date_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    // Tombol untuk mengajukan penyesuaian
                    Action::make('requestAdjustment')
                        ->label('Ajukan Penyesuaian')
                        ->icon('heroicon-o-paper-airplane')
                        ->disabled(function (Attendance $record) {
                            // Nonaktifkan jika sudah pernah mengajukan untuk hari ini
                            if ($record->adjustment_status !== null) {
                                return true;
                            }

                            // Nonaktifkan jika sudah mencapai batas bulanan
                            $startOfMonth = now()->startOfMonth();
                            $endOfMonth = now()->endOfMonth();
                            $monthlyRequestCount = Attendance::where('employee_id', $record->employee_id)
                                ->whereNotNull('adjustment_status') // Hitung semua yang pernah diajukan (pending, approved, rejected)
                                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                                ->count();
                            
                            return $monthlyRequestCount >= 3;
                        })
                        ->tooltip(function (Attendance $record) {
                            // Tampilkan pesan jika sudah pernah mengajukan hari ini
                            if ($record->adjustment_status !== null) {
                                return 'Anda sudah mengajukan penyesuaian untuk tanggal ini.';
                            }

                            // Tampilkan pesan jika sudah mencapai batas bulanan
                            $startOfMonth = now()->startOfMonth();
                            $endOfMonth = now()->endOfMonth();
                            $monthlyRequestCount = Attendance::where('employee_id', $record->employee_id)
                                ->whereNotNull('adjustment_status')
                                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                                ->count();

                            if ($monthlyRequestCount >= 3) {
                                return 'Anda telah mencapai batas maksimal 3 penyesuaian per bulan.';
                            }
                            return null;
                        })
                        ->form([
                            TimePicker::make('adjusted_check_in_at')->label('Jam Masuk Baru')->seconds(false),
                            TimePicker::make('adjusted_check_out_at')->label('Jam Pulang Baru')->seconds(false),
                            Textarea::make('adjustment_reason')->label('Alasan')->required(),
                        ])
                        ->action(function (Attendance $record, array $data) {
                            $record->update([
                                'adjusted_check_in_at' => $data['adjusted_check_in_at'],
                                'adjusted_check_out_at' => $data['adjusted_check_out_at'],
                                'adjustment_reason' => $data['adjustment_reason'],
                                'adjustment_status' => 'pending',
                            ]);
                            Notification::make()->title('Permintaan penyesuaian berhasil diajukan.')->success()->send();
                        }),

                    // Tombol untuk meninjau permintaan
                    Action::make('reviewAdjustment')
                        ->label('Tinjau Permintaan')
                        ->icon('heroicon-o-magnifying-glass')
                        ->visible(fn (Attendance $record) => $record->adjustment_status === 'pending')
                        ->authorize('review_attendance_adjustment')
                        ->modalHeading('Tinjau Penyesuaian Absensi')
                        ->form(function (Attendance $record) {
                            return [
                                Placeholder::make('current_data')
                                    ->label('Data Absensi Saat Ini')
                                    ->content(
                                        'Check-in: ' . ($record->check_in_at ? $record->check_in_at->format('H:i:s') : '-') .
                                        ' | Check-out: ' . ($record->check_out_at ? $record->check_out_at->format('H:i:s') : '-')
                                    ),
                                Placeholder::make('requested_data')
                                    ->label('Data yang Diminta')
                                    ->content(
                                        'Check-in: ' . ($record->adjusted_check_in_at ? \Carbon\Carbon::parse($record->adjusted_check_in_at)->format('H:i:s') : '-') .
                                        ' | Check-out: ' . ($record->adjusted_check_out_at ? \Carbon\Carbon::parse($record->adjusted_check_out_at)->format('H:i:s') : '-')
                                    ),
                                Placeholder::make('adjustment_reason')
                                    ->label('Alasan Penyesuaian')
                                    ->content($record->adjustment_reason ?: 'Tidak ada alasan diberikan.'),
                            ];
                        })
                        ->action(null)
                        ->modalActions([
                            Action::make('approve')
                                ->label('Setujui')
                                ->color('success')
                                ->action(function (Attendance $record) {
                                    $record->update([
                                        'check_in_at' => $record->adjusted_check_in_at,
                                        'check_out_at' => $record->adjusted_check_out_at,
                                        'adjustment_status' => 'approved',
                                    ]);
                                    Notification::make()->title('Penyesuaian berhasil disetujui')->success()->send();
                                }),
                            Action::make('reject')
                                ->label('Tolak')
                                ->color('danger')
                                ->form([
                                    Textarea::make('adjustment_rejection_reason')
                                        ->label('Alasan Penolakan')
                                        ->required(),
                                ])
                                ->action(function (Attendance $record, array $data) {
                                    $record->update([
                                        'adjustment_status' => 'rejected',
                                        'adjustment_rejection_reason' => $data['adjustment_rejection_reason'],
                                    ]);
                                    Notification::make()->title('Penyesuaian berhasil ditolak')->warning()->send();
                                }),
                        ]),
                ]),
            ]);
    }
}
