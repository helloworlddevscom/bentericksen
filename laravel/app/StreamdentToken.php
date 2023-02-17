<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamdentToken extends Model
{
    protected $table = 'streamdent_token';

    protected $dates = ['token_expiration'];

    protected $fillable = [
        'access_token',
        'token_expiration'
    ];
}
