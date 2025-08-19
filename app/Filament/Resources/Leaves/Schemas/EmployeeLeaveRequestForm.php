<?php

namespace App\Filament\Resources\Leaves\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;


class EmployeeLeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Form ini tidak memiliki field 'employee_id' karena akan diisi otomatis
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'annual' => 'Cuti Tahunan',
                        'sick' => 'Izin Sakit',
                        'unpaid' => 'Cuti Tidak Dibayar',
                        'other' => 'Lainnya',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('attachment_path')
                    ->label('Lampiran (opsional)')
                    ->helperText('Contoh: Surat keterangan sakit.')
                    ->directory('leave-attachments')
                    ->visibility('private'),
            ])->columns(2);
    }
}
