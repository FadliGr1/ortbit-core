<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentTarget extends Model
{
    use HasFactory;

    /**
     * Menonaktifkan proteksi mass assignment.
     */
    protected $guarded = [];

    /**
     * Relasi ke brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
