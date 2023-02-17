<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ClassificationUpdates extends Model
{
    protected $table = 'classification_updates';

    protected $dates = ['effective_at'];

    protected $fillable = [
        'user_id',
        'classification_id',
        'effective_at',
    ];

    public function setEffectiveAtAttribute($value)
    {
        return $this->attributes['effective_at'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
