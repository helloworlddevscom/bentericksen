<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamdentAPIUserSchema extends Model
{
    protected $guarded = [];
    
    protected $attributes = [
        'id' => 0,
        'login' => '',
        'fname' => '',
        'lname' => '',
        'phone' => '',
        'mobile' => '',
        'email' => '',
        'password' => '',
        'is_active' => false,
        'is_editor' => false
    ];
}
