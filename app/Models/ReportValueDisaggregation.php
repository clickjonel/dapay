<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValueDisaggregation extends Model
{
    protected $fillable = [
        'report_value_id',
        'disaggregation_id',
        'value',
    ];

    public function disaggregation()
    {
        return $this->belongsTo(Disaggregation::class, 'disaggregation_id', 'id');
    }
}
