<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentArticle extends Model
{
    use HasFactory;

    /**
     * Menonaktifkan proteksi mass assignment.
     */
    protected $guarded = [];

    /**
     * Casts untuk tipe data.
     */
    protected $casts = [
        'publish_date' => 'datetime',
        'seo_checklist' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function brief(): BelongsTo
    {
        return $this->belongsTo(ContentBrief::class, 'brief_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
