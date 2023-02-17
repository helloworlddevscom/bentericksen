<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimeOff.
 *
 * Model class for time off requests
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $request_type  'timeoff' = time off request, 'leave' = leave of absence
 * @property string $status
 * @property string|null $reason
 * @property string $vacation
 * @property string $pto
 * @property string $remaining
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $note
 * @property int $request_pto_time
 * @property-read \App\User $user
 *
 * @see \App\User
 */
class TimeOff extends Model
{
    protected $table = 'timeoff_requests';

    protected $fillable = [
        'user_id',
        'type',
        'request_type',
        'status',
        'end_at',
        'start_at',
        'reason',
        'vacation',
        'pto',
        'remaining',
        'note',
        'request_pto_time',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    /**
     * User relation.
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Mutator for "start_at" field.
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    public function setStartAtAttribute($value)
    {
        return $this->attributes['start_at'] = Carbon::createFromTimestamp(strtotime($value));
    }

    /**
     * Mutator for "end_at" field.
     *
     * @param $value
     *
     * @return \Carbon\Carbon
     */
    public function setEndAtAttribute($value)
    {
        return $this->attributes['end_at'] = Carbon::createFromTimestamp(strtotime($value));
    }
}
