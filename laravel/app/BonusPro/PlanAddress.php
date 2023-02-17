<?php

namespace App\BonusPro;

use Illuminate\Database\Eloquent\Model;

class PlanAddress extends Model
{
    protected $table = 'bonuspro_plan_address';

    protected $fillable = [
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
    ];

    /**
     * Defining 1-1 relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
