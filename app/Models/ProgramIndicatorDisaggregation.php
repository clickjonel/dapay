<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramIndicatorDisaggregation extends Model
{
    protected $fillable = [
        'program_indicator_id',
        'disaggregation_id',
        'totalable'
    ];
    public $timestamps = false;
    protected $table = 'program_indicator_disaggregations';
    protected $casts = ['totalable' => 'boolean'];

    // public function subProgram()
    // {
    //     return $this->belongsTo(SubProgram::class,'id','id');
    // }
}
