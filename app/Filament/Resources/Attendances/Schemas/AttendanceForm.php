<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use App\Models\Employee;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // [DIUBAH] Komponen Section dihapus agar layout menjadi full-width
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(
                        Employee::with('user')->get()->pluck('user.name', 'id')
                    )
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),
                TimePicker::make('check_in_at')
                    ->label('Jam Masuk')
                    ->seconds(false),
                TimePicker::make('check_out_at')
                    ->label('Jam Pulang')
                    ->seconds(false),
                Select::make('status')
                    ->options([
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'absent' => 'Tidak Hadir',
                        'on_leave' => 'Cuti',
                    ])
                    ->required()
                    ->default('present'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
