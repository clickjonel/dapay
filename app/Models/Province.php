<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function municipalities()
    {
        return $this->hasMany(Municipality::class,'province_id','id');
    }

    public function barangays()
    {
         return $this->hasMany(Barangay::class,'province_id','id');
    }
}
