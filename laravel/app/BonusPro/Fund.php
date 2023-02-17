<?php

namespace App\BonusPro;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $table = 'bonuspro_fund';

    protected $fillable = [
        'plan_id',
        'fund_id',
        'fund_name',
        'fund_start_month',
        'fund_start_year',
        'fund_type',
        'fund_amount',
    ];

    /**
     * BonusPro Plan relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
