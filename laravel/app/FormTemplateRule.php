<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormTemplateRule extends Model
{
  protected $table = 'form_template_rules';
  
  protected $fillable = [
    'name',
    'expression'
  ];
}