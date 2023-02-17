<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'short_answer',
        'long_answer',
        'category_id',
        'state',
        'business_type',
    ];
}
