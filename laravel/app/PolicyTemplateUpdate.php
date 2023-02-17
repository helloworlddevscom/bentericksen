<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyTemplateUpdate extends Model
{
    protected $table = 'policy_template_updates';

    protected $fillable = [
        'id',
        'content',
        'category_id',
        'admin_name',
        'manual_name',
        'benefit_type',
        'effective_date',
        'end_date',
        'state',
        'min_employee',
        'max_employee',
        'requirement',
        'parameters',
        'tags',
        'created_at',
        'updated_at',
        'status',
        'order',
        'alternate_name',
        'template_id',
    ];

    protected $dates = [
        'effective_date',
        'end_date',
    ];

    /**
     * Mutator for "Requirement" property.
     *
     * @param $value
     */
    public function setRequirementAttribute($value)
    {
        $this->attributes['requirement'] = json_encode($value);
    }

    /**
     * Accessor for "Requirement" property.
     *
     * @return mixed
     */
    public function getRequirementAttribute($value)
    {
        return ! empty($value) ? json_decode($value) : [];
    }
}
