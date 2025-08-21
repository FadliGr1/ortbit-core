<?php

namespace App\Filament\Resources\Payslips;

use App\Filament\Resources\Payslips\Pages\ListPayslips;
use App\Filament\Resources\Payslips\Schemas\PayslipForm;
use App\Filament\Resources\Payslips\Tables\PayslipsTable;
use App\Models\Payslip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

class PayslipResource extends Resource
{
    protected static ?string $model = Payslip::class;
    protected static ?string $recordTitleAttribute = 'payslip';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Heart;
    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';
    protected static ?string $navigationLabel = 'Payslips';
    protected static ?int $navigationSort = 4;
    
    protected static array $with = ['employee.user', 'employee.departments'];

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika pengguna memiliki peran Super Admin, tampilkan semua data.
        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        // Cari data karyawan yang terhubung dengan pengguna ini.
        $employee = Employee::where('user_id', $user->id)->first();

        // Jika pengguna ini tidak terdaftar sebagai karyawan, jangan tampilkan apa pun.
        if (!$employee) {
            return $query->whereNull('id'); // Trik untuk mengembalikan hasil kosong
        }

        // Tampilkan hanya data slip gaji milik karyawan ini.
        return $query->where('employee_id', $employee->id);
    }

    public static function form(Schema $schema): Schema
    {
        return PayslipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayslipsTable::configure($table);
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
            'index' => ListPayslips::route('/'),
            // 'create' => CreatePayslip::route('/create'),
            // 'edit' => EditPayslip::route('/{record}/edit'),
        ];
    }

    
}
