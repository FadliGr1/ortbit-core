<?php

namespace App\Filament\Resources\Leaves\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Textarea;
use App\Models\Leave;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;
use Filament\Actions\ViewAction;
class LeavesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'annual' => 'Cuti Tahunan',
                        'sick' => 'Izin Sakit',
                        'unpaid' => 'Cuti Tidak Dibayar',
                        default => 'Lainnya',
                    })
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Dari Tanggal')
                    ->date('d M Y'),
                TextColumn::make('end_date')
                    ->label('Sampai Tanggal')
                    ->date('d M Y'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('rejection_reason')
                    ->badge()
                    ->color('danger')
                    ->label('Remark'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipe Cuti')
                    ->options([
                        'annual' => 'Cuti Tahunan',
                        'sick' => 'Izin Sakit',
                        'unpaid' => 'Cuti Tidak Dibayar',
                        'other' => 'Lainnya',
                    ])
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->authorize('update'),
                    // Tombol untuk Approval
                    Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Leave $record) => $record->status === 'pending')
                        ->action(function (Leave $record) {
                            // Logika pengurangan kuota hanya untuk 'Cuti Tahunan'
                            if ($record->type === 'annual') {
                                $startDate = Carbon::parse($record->start_date);
                                $endDate = Carbon::parse($record->end_date);
                                $leaveDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
                                    return !$date->isWeekend();
                                }, $endDate) + 1;

                                $employee = $record->employee;
                                if ($employee->leave_quota >= $leaveDays) {
                                    $employee->leave_quota -= $leaveDays;
                                    $employee->save();
                                } else {
                                    Notification::make()->title('Gagal! Kuota cuti karyawan tidak mencukupi.')->danger()->send();
                                    return;
                                }
                            }

                            $record->update([
                                'status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);
                            Notification::make()->title('Pengajuan berhasil disetujui.')->success()->send();
                        })
                        ->authorize('approve_leave'),
                    // Tombol untuk Penolakan
                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Leave $record) => $record->status === 'pending')
                        ->form([
                            Textarea::make('rejection_reason')->label('Alasan Penolakan')->required(),
                        ])
                        ->action(function (Leave $record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'rejection_reason' => $data['rejection_reason'],
                            ]);
                            Notification::make()->title('Pengajuan berhasil ditolak.')->warning()->send();
                        })
                        ->authorize('reject_leave'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->authorize('deleteAny'),
                ]),
            ]);
    }
}
