<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Enkripsi password saat disimpan
                            ->dehydrated(fn ($state) => filled($state)) // Hanya proses jika field diisi
                            ->required(fn (string $context): bool => $context === 'create'), // Wajib diisi hanya saat membuat pengguna baru
                    ]),

                Section::make('Peran (Roles)')
                    ->schema([
                        Select::make('roles')
                            ->label('Peran Pengguna')
                            ->relationship('roles', 'name') // Menghubungkan ke relasi 'roles'
                            ->multiple() // Izinkan memilih lebih dari satu peran
                            ->preload() // Langsung muat semua pilihan saat halaman dibuka
                            ->searchable(),
                    ]),
            ]);
    }
}
