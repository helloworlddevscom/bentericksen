<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    protected $table = 'form_templates';

    protected $upload_field_name = 'file_upload';

    protected $upload_path = 'bentericksen/uploads/forms';

    protected $fillable = [
        'business_id',
        'name',
        'number',
        'category_id',
        'type',
        'form_type',
        'file_name',
        'description',
        'is_default',
        'status',
        'state',
        'min_employee',
        'max_employee',
        'industries'
    ];

    /**
     * Category Relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * State Mutator.
     * @param $value
     */
    public function setStateAttribute($value)
    {
      $this->attributes['state'] = json_encode($value);
    }

    public function getStateAttribute($value)
    {
      return json_decode($value);
    }


    /**
     * State Mutator.
     * @param $value
     */
    public function setIndustriesAttribute($value)
    {
      $this->attributes['industries'] = json_encode($value);
    }

    public function getIndustriesAttribute($value)
    {
      return json_decode($value);
    }

    /**
     * Returns upload field name.
     *
     * @return string
     */
    public function getUploadFieldName()
    {
        return $this->upload_field_name;
    }

    /**
     * Returns upload path.
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->upload_path;
    }

    /**
     * Checks which forms are applicable to business.
     *
     * @param $business Business
     *
     * @param bool $returnObjects
     *
     * @return array
     */
    public function getApplicableTemplates($business, $returnObjects = false)
    {
        $forms = [];

        foreach ($this->all() as $template) {
            $states = $template->state;
            if (! $states
                || in_array($business->state, $states)
                || in_array('ALL', $states)
                || (in_array('Non-MT', $states) && $business->state !== 'MT')
            ) {
                array_push($forms, $returnObjects ? $template : $template->id);
            }
        }

        if (! $returnObjects) {
            asort($forms);
        }

        return $forms;
    }
}
