<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'employee_id',
        'date',
        'check_in_at',
        'check_out_at',
        'adjusted_check_in_at',
        'adjusted_check_out_at',
        'adjustment_reason',
        'adjustment_status',
        'adjustment_rejection_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        // We don't cast the 'adjusted' columns because they can be null
        // and are handled as strings from the form.
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
