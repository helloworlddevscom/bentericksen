<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $table = 'help';

    protected $fillable = ['title', 'section', 'sub_section', 'answer'];
}
