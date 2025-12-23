<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalIndicators extends Model
{
    protected $fillable = [
        'name',
        'active',
    ];
    public $timestamps = false;
    protected $table = 'organizational_indicators';
}
