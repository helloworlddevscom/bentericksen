<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paperwork extends Model
{
    protected $table = 'paperwork';

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_paperwork');
    }
}
