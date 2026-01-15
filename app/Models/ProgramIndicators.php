<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramIndicators extends Model
{
    protected $fillable = [
        'name',
        'sub_program_id',
        'active',
    ];
    public $timestamps = false;
    protected $table = 'program_indicators';

    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class,'sub_program_id','id');
    }

    public function disaggregations()
    {
        return $this->belongsToMany(
            Disaggregation::class,
            'program_indicator_disaggregations',
            'program_indicator_id',
            'disaggregation_id'
        )
        ->withPivot(['totalable']);
    }
}
