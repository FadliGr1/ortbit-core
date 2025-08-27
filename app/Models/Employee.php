<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'join_date' => 'date',
        // Enkripsi otomatis untuk data sensitif
        'bank_account_details' => 'encrypted',
    ];

    /**
     * Setiap data karyawan terhubung ke satu akun pengguna.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_employee');
    }

    /**
     * Relasi untuk mendapatkan data manajer (atasan) dari seorang karyawan.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Relasi untuk mendapatkan semua bawahan dari seorang manajer.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
    
}
