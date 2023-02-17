<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LicensureCertifications extends Model
{
    protected $table = 'licensure_certifications';

    protected $fillable = [
        'business_id',
        'name',
        'status',
        'type',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_licensure_certifications');
    }
}
