<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SalaryUpdates extends Model
{
    protected $table = 'salary_updates';

    protected $dates = ['effective_at'];

    protected $fillable = [
        'user_id',
        'salary',
        'rate',
        'effective_at',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setEffectiveAtAttribute($value)
    {
        return $this->attributes['effective_at'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
