<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValue extends Model
{
    protected $fillable = [
        'report_id',
        'sub_program_id',
        'program_indicator_id',
        'organizational_indicator_id',
        'disaggregation_id',
        'indicator_type',
        'value'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class,'report_id','id');
    }
    public function subProgram()
    {
        return $this->belongsTo(SubProgram::class,'sub_program_id','id');
    }

    public function programIndicator()
    {
        return $this->belongsTo(ProgramIndicators::class,'program_indicator_id','id');
    }

    public function disaggregation()
    {
        return $this->belongsTo(Disaggregation::class,'disaggregation_id','id');
    }

}
