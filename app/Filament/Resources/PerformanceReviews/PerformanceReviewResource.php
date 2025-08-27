<?php

namespace App\Filament\Resources\PerformanceReviews;

use App\Filament\Resources\PerformanceReviews\Pages\ListPerformanceReviews;
use App\Filament\Resources\PerformanceReviews\Schemas\PerformanceReviewForm;
use App\Filament\Resources\PerformanceReviews\Tables\PerformanceReviewsTable;
use App\Filament\Resources\PerformanceReviews\Pages\CreatePerformanceReview;
use App\Filament\Resources\PerformanceReviews\Pages\EditPerformanceReview;
use App\Models\PerformanceReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;


class PerformanceReviewResource extends Resource
{
    protected static ?string $model = PerformanceReview::class;
    protected static ?string $recordTitleAttribute = 'performance';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowTrendingUp;
    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';
    protected static ?string $navigationLabel = 'Performance';
    protected static ?int $navigationSort = 1;

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
        return PerformanceReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerformanceReviewsTable::configure($table);
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
            'index' => ListPerformanceReviews::route('/'),
            'create' => CreatePerformanceReview::route('/create'),
            'edit' => EditPerformanceReview::route('/{record}/edit'),
        ];
    }
}
