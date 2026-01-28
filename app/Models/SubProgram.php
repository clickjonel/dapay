<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProgram extends Model
{
    protected $fillable = [
        'name',
        'active',
        'program_id'
    ];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id','id');
    }

    public function programIndicators()
    {
        return $this->hasMany(ProgramIndicators::class,'sub_program_id','id');
    }

    public function reportValues()
    {
        return $this->hasMany(ReportValue::class,'sub_program_id','id');
    }

}
