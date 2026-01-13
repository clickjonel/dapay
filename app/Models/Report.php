<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'total_clients',
        'total_returning_clients',
        'submitted_by',
        'date',
        'barangay_id',
    ];

    public function values()
    {
        return $this->hasMany(ReportValue::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
    
}
