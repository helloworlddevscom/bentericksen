<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'user_attendance';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'status',
        'note',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    /**
     * User Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Start Date Mutator.
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    public function setStartDateAttribute($value)
    {
        return $this->attributes['start_date'] = Carbon::createFromTimestamp(strtotime($value));
    }

    /**
     * End Date Mutator.
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    public function setEndDateAttribute($value)
    {
        return $this->attributes['end_date'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
