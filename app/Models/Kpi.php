<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $guarded = [];
    public function performanceReview()
    {
        return $this->belongsTo(PerformanceReview::class);
    }
}
