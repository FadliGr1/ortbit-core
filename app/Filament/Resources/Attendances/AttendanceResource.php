<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';
    protected static ?string $navigationLabel = 'Attendances';
    protected static ?int $navigationSort = 2; 

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika pengguna memiliki peran Super Admin (atau peran HR nanti), tampilkan semua data.
        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        // Cari data karyawan yang terhubung dengan pengguna ini.
        $employee = Employee::where('user_id', $user->id)->first();

        // Jika pengguna ini tidak terdaftar sebagai karyawan, jangan tampilkan apa pun.
        if (!$employee) {
            return $query->whereNull('id'); // Trik untuk mengembalikan hasil kosong
        }

        // Tampilkan hanya data absensi milik karyawan ini.
        return $query->where('employee_id', $employee->id);
    }

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            // 'create' => CreateAttendance::route('/create'),
            // 'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
