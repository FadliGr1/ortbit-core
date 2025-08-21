<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Attendance;

class HrStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Menghitung total karyawan aktif
        $totalEmployees = Employee::where('status', 'active')->count();

        // Menghitung karyawan yang sedang cuti hari ini
        $onLeaveToday = Employee::where('status', 'on_leave')
            ->count();
            
        // Menghitung total pengajuan yang menunggu persetujuan
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $pendingAdjustments = Attendance::where('adjustment_status', 'pending')->count();
        $totalPendingRequests = $pendingLeaves + $pendingAdjustments;

        return [
            Stat::make('Total Karyawan Aktif', $totalEmployees)
                ->description('Jumlah seluruh karyawan yang aktif')
                ->icon('heroicon-o-user-group'),
            Stat::make('Karyawan Cuti Hari Ini', $onLeaveToday)
                ->description('Jumlah karyawan yang sedang cuti')
                ->color('warning')
                ->icon('heroicon-o-arrow-trending-down'),
            Stat::make('Pengajuan Pending', $totalPendingRequests)
                ->description('Cuti & Penyesuaian Absensi')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle'),
        ];
    }
}
