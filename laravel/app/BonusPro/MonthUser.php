<?php

namespace App\BonusPro;

use Illuminate\Database\Eloquent\Model;

class MonthUser extends Model
{
    protected $table = 'bonuspro_month_user';

    protected $fillable = [
        'month_id',
        'user_id',
        'hours_worked',
        'gross_pay',
        'amount_received',
        'percentage',
    ];

    /**
     * Mutator for hours_worked argument.
     */
    public function setHoursWorkedAttribute($value)
    {
        return $this->attributes['hours_worked'] = str_replace(',', '', $value);
    }

    /**
     * Mutator for hours_worked argument.
     */
    public function setGrossPayAttribute($value)
    {
        return $this->attributes['gross_pay'] = str_replace(',', '', $value);
    }

    /**
     * User Month relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    /**
     * User Month relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaryData()
    {
        return $this->hasMany(self::class, 'month_id');
    }
}
