<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    // Izinkan semua kolom untuk diisi (mass assignable)
    protected $guarded = [];

    // Otomatis ubah nilai boolean ke tipe data yang benar
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
