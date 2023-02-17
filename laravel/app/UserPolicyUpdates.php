<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPolicyUpdates extends Model
{
    protected $table = 'user_policy_updates';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function scopeAccepted($query)
    {
      return $query->where('accepted_at', '<>', '0000-00-00 00:00:00');
    }
}
