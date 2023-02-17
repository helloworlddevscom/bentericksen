<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'business_id',
        'name',
        'grouping',
        'order',
    ];

    /**
     * Form Template Relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formTemplates()
    {
        return $this->hasMany(FormTemplate::class, 'category_id');
    }
}
