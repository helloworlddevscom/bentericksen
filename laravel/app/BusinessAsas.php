<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessAsas extends Model
{
    protected $table = 'business_asas';

    protected $dates = [
        'expiration',
    ];

    protected $fillable = [
        'type',
        'expiration',
        'business_id',
    ];

    /**
     * Business relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Expiration date attribute mutator.
     *
     * @param $value
     */
    public function setExpirationAttribute($value)
    {
        $string = strtotime($value);
        $this->attributes['expiration'] = date('Y-m-d', $string).' 00:00:00';
    }

    /**
     * @return string
     */
    public function billingPeriod() {
        if(str_contains($this->type, "-")) {
            return explode("-", $this->type)[0];
        }
        return "annual";
    }
}
