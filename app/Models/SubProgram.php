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
}
