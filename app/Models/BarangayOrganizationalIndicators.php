<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOrganizationalIndicators extends Model
{
    protected $table = 'barangay_org_indicators';
    public $timestamps = false;
    protected $fillable = [
        'barangay_id',
        'organizational_indicator_id',
        'value',
        'remarks',
    ];


}
