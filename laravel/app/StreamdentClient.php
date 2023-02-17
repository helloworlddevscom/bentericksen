<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamdentClient extends Model
{
    protected $table = 'streamdent_client';

    /**
     * Get the business that this Streamdent Business belongs to.
     */
    public function business()
    {
        return $this->belongsTo('App\Business');
    }
}
