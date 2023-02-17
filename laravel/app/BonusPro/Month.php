<?php

namespace App\BonusPro;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'bonuspro_month';

    protected $fillable = [
        'month',
        'plan_id',
        'year',
        'production_amount',
        'collection_amount',
        'production_collection_average',
        'staff_percentage',
        'hygiene_production_amount',
        'hygiene_percentage',
        'finalized',
    ];

    /**
     * Month Plan relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo'
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Month User relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employeeData()
    {
        return $this->hasMany(MonthUser::class, 'month_id');
    }

    /**
     * Month Fund relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function funds()
    {
        return $this->hasMany(MonthFund::class, 'month_id');
    }

    /**
     * Mutator for Production_Attribute argument.
     */
    public function setProductionAmountAttribute($value)
    {
        return $this->attributes['production_amount'] = str_replace(',', '', $value);
    }

    /**
     * Mutator for Collection_Amount argument.
     */
    public function setCollectionAmountAttribute($value)
    {
        return $this->attributes['collection_amount'] = str_replace(',', '', $value);
    }

    /**
     * Mutator for Hygiene_Production_Amount argument.
     */
    public function setHygieneProductionAmountAttribute($value)
    {
        return $this->attributes['hygiene_production_amount'] = str_replace(',', '', $value);
    }

    /**
     * Close current Month.
     *
     * @return $this
     */
    public function closeMonth()
    {
        $this->finalized = true;
        $this->staff_percentage = $this->plan->staff_bonus_percentage;
        $this->hygiene_percentage = $this->plan->hygiene_bonus_percentage;

        $this->save();

        return $this;
    }
}
