<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'base_salary',
        'transport_allowance',
        'meal_allowance',
        'other_allowances',
        'bpjs_health_deduction',
        'bpjs_employment_deduction',
        'tax_deduction',
        'loan_deduction',
        'other_deductions',
        'effective_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'other_allowances' => 'array',
        'other_deductions' => 'array',
        'effective_date' => 'date',
    ];

    /**
     * Get the employee that owns the payroll record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
