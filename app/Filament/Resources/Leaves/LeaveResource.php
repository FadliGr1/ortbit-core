<?php

namespace App\Filament\Resources\Leaves;

use App\Filament\Resources\Leaves\Pages\CreateLeave;
use App\Filament\Resources\Leaves\Pages\EditLeave;
use App\Filament\Resources\Leaves\Pages\ListLeaves;
use App\Filament\Resources\Leaves\Schemas\LeaveForm;
use App\Filament\Resources\Leaves\Tables\LeavesTable;
use App\Models\Leave;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;
    protected static ?string $recordTitleAttribute = 'reason';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;
    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';
    protected static ?string $navigationLabel = 'Cuti & Izin';
    protected static ?int $navigationSort = 3;


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
        return LeaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeavesTable::configure($table);
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
            'index' => ListLeaves::route('/'),
            // 'create' => CreateLeave::route('/create'),
            // 'edit' => EditLeave::route('/{record}/edit'),
        ];
    }
}
