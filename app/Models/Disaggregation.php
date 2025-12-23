<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disaggregation extends Model
{
    protected $fillable = [
        'name',
        'indicator_id',
        'active',
        'type'
    ];
    public $timestamps = false;
}
