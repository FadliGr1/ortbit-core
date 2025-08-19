<?php

namespace App\Filament\Resources\Employees;

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'position';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';
    protected static ?string $navigationLabel = 'Employees';
    protected static ?int $navigationSort = 0;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record?->user?->name;
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()->can('view_any_employee');
    // }

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);

        
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            // 'create' => CreateEmployee::route('/create'),
            // 'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
