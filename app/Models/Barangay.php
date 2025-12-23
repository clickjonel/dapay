<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'province_id',
        'municipality_id',
        'name',
        'pk_status',
        'is_gida',
        'is_pk_site',
        'deployed_hrh',
        'latitude',
        'longitude',
        'total_purok',
        'target_purok',
        'target_households',
        'target_individuals',
        // 'total_lsa_conducted',
        // 'total_ssa_conducted',
    ];
}
