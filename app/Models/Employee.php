<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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
    
}
