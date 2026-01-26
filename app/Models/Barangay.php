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
        'mov_gdrive_link'
    ];

    protected $casts = [
        'is_gida' => 'boolean',
        'is_pk_site' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function organizationalIndicators()
    {
        return $this->belongsToMany(
            OrganizationalIndicators::class,
            'barangay_org_indicators',
            'barangay_id',
            'organizational_indicator_id'
        )->withPivot('value', 'remarks');
    }
}
