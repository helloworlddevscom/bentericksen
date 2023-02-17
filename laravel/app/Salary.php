<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'salaries';

    protected $fillable = [
        'user_id',
        'salary',
        'rate',
        'reason'
    ];

    protected $dates = [
        'effective_at',
        'salary_updated',
    ];

    /**
     * User Eloquent relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Salary Attribute Mutator.
     *
     * @param $value
     *
     * @return string
     */
    public function setSalaryAttribute($value)
    {
        $string = str_replace(',', '', $value);

        return $this->attributes['salary'] = number_format($string, 2, '.', '');
    }
}
