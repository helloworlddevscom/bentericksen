<?php

namespace App\BonusPro;

use Illuminate\Database\Eloquent\Model;

class MonthFund extends Model
{
    protected $table = 'bonuspro_month_fund';

    protected $fillable = [
        'month_id',
        'fund_id',
        'amount_received',
    ];

    /**
     * Fund Month relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    /**
     * Fund Month relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fundData()
    {
        return $this->hasMany(self::class, 'month_id');
    }
}
