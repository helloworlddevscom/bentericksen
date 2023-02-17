<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'forms';

    /**
     * Fillable fields.
     * @var array
     */
    protected $fillable = [
        'file',
        'folder',
        'status',
        'edited',
        'description',
        'template_id',
        'business_id',
    ];

    /**
     * Business Relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'id');
    }

    /**
     * FormTemplate Relationship.
     */
    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'template_id');
    }
}
