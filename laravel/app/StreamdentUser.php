<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamdentUser extends Model
{
    protected $table = 'streamdent_user';

    /**
     * Get the user that this Streamdent User belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
