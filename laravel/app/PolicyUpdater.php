<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyUpdater extends Model
{
    protected $table = 'policy_updater';

    protected $guarded = [];

    public function contacts()
    {
        return $this->hasMany(\App\PolicyUpdaterContact::class);
    }
}
