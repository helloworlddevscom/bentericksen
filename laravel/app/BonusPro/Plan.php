<?php

namespace App\BonusPro;

use App\Business;
use App\Notifications\BonusProPasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Plan extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable;

    protected $hidden = [
        'password',
    ];

    protected $table = 'bonuspro_plan';

    protected $fillable = [
        'plan_name',
        'plan_id',
        'start_month',
        'start_year',
        'password',
        'created_by',
        'business_id',
        'distribution_type',
        'rolling_average',
        'staff_bonus_percentage',
        'hygiene_bonus_percentage',
        'type_of_practice',
        'hygiene_plan',
        'separate_fund',
        'use_business_address',
        'status',
        'completed',
        'draft',
        'current_step',
    ];

    /**
     * Plan address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(PlanAddress::class);
    }

    /**
     * Get the users/employees that belong to this business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bonuspro_plan_user')->orderBy('last_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reset_user()
    {
        return $this->belongsTo(User::class, 'reset_by');
    }

    /**
     * Plan Month address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function months()
    {
        return $this->hasMany(Month::class);
    }

    /**
     * Fund relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function funds()
    {
        return $this->hasMany(Fund::class);
    }

    /**
     * Plan Business relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Name attribute mutator.
     * @param $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        return $this->attributes['name'] = ucwords($value);
    }

    /**
     * start_date attribute mutator.
     * @param $value
     * @return string
     */
    public function setStartDateAttribute($value)
    {
        return $this->attributes['start_date'] = Carbon::createFromTimestamp(strtotime($value));
    }

    /**
     * Password attribute mutator.
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }

    public function username()
    {
        return 'id';
    }

    public function __get($property)
    {
        if ($property === 'email') {
            return ! empty($this->reset_user) ? $this->reset_user->email : $this->user->email;
        } else {
            return parent::__get($property);
        }
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new BonusProPasswordReset($token, $this));
    }

    /**
     * Builds collection of only running month.
     * remove first 6 months of setup data.
     */
    public function getRunningMonths()
    {
        // Considering only running months after initialization.
        return $this->months->where('finalized', 1)->slice(6);
    }

}
