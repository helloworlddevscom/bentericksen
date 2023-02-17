<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyUpdaterContact extends Model
{
    protected $table = 'policy_updater_contacts';

    protected $guarded = [];

    public function scopeAdditional($query)
    {
        return $query->where('contact_type', 'additional');
    }

    public function scopePrimary($query)
    {
        return $query->where('contact_type', 'primary');
    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'email', 'email');
    }
}
