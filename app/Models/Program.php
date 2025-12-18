<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name',
        'active'
    ];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function subPrograms()
    {
        return $this->hasMany(SubProgram::class,'program_id','id');
    }
}
