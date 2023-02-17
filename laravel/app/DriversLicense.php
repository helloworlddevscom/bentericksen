<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DriversLicense extends Model
{
    protected $table = 'driver_licenses';

    protected $guarded = [];

    protected $dates = ['expiration', 'policy_expiration'];

    public function setExpirationAttribute($value)
    {
        return $this->attributes['expiration'] = Carbon::createFromTimestamp(strtotime($value));
    }

    public function setPolicyExpirationAttribute($value)
    {
        return $this->attributes['policy_expiration'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
