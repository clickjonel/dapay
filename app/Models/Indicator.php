<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $fillable = [
        'name',
        'sub_program_id',
        'active',
        'is_legacy'
    ];
    public $timestamps = false;

}
