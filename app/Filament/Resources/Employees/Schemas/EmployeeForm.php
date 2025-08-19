<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Data Karyawan')->tabs([
                    Tabs\Tab::make('Informasi Pekerjaan')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Select::make('user_id')
                                ->label('Akun Pengguna')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->createOptionForm([
                                    TextInput::make('name')->required(),
                                    TextInput::make('email')->email()->required()->unique(),
                                    TextInput::make('password')->password()->required(),
                                ]),
                            TextInput::make('leave_quota')
                            ->label('Kuota Cuti Tahunan')
                            ->numeric() // Hanya menerima angka
                            ->minValue(0) // Nilai minimal 0
                            ->required()
                            ->default(12),
                            Select::make('departments')
                            ->relationship('departments', 'name')
                            ->multiple()
                            ->preload()
                            ->label('Departemen')
                            ->searchable()
                            ->required(),
                            TextInput::make('position')
                                ->label('Jabatan / Posisi')
                                ->required(),
                            DatePicker::make('join_date')
                                ->label('Tanggal Bergabung')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'active' => 'Aktif',
                                    'on_leave' => 'Cuti',
                                    'resigned' => 'Resign',
                                ])
                                ->required()
                                ->default('active'),
                        ])->columns(2),

                    Tabs\Tab::make('Data Pribadi & Kontak')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextInput::make('phone_number')
                                ->label('Nomor Telepon'),
                            TextInput::make('nik')
                                ->label('NIK (Nomor Induk Kependudukan)')
                                ,
                            TextInput::make('npwp')
                                ->label('NPWP (Nomor Pokok Wajib Pajak)'),
                            Textarea::make('address')
                                ->label('Alamat Lengkap')
                                ->columnSpanFull(),
                        ])->columns(2),

                    Tabs\Tab::make('Informasi Keuangan')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Textarea::make('bank_account_details')
                                ->label('Detail Rekening Bank')
                                ->placeholder("Contoh:\nBCA - 1234567890\na/n John Doe")
                                ->rows(4)
                                ->helperText('Informasi ini akan dienkripsi secara otomatis.'),
                        ]),

                    Tabs\Tab::make('Dokumen')
                        ->icon('heroicon-o-document-arrow-up')
                        ->schema([
                            FileUpload::make('ktp_path')
                                ->label('File KTP')
                                ->directory('employee-documents/ktp')
                                ->visibility('private'),
                            FileUpload::make('npwp_path')
                                ->label('File NPWP')
                                ->directory('employee-documents/npwp')
                                ->visibility('private'),
                            FileUpload::make('contract_path')
                                ->label('File Kontrak Kerja')
                                ->directory('employee-documents/contracts')
                                ->visibility('private'),
                        ])->columns(3),
                ])->columnSpanFull(),
            ]);
    }
}
