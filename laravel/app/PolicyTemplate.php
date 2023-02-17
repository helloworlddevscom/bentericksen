<?php

namespace App;

use Carbon\Carbon;
use Bentericksen\Traits\HTMLPolicyCleanup;
use Illuminate\Database\Eloquent\Model;
use Bentericksen\Traits\WYSIWYGSpaceStrip;

class PolicyTemplate extends Model
{
    use HTMLPolicyCleanup;
    use WYSIWYGSpaceStrip;

    protected $table = 'policy_templates';

    protected $guarded = [];

    protected $fillable = [];

    protected $dates = [
        'effective_date',
        'end_date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
     * Mutator to cleanup/remove inline formatting from CKEditor from content.
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $value = $this->stripSpaces($value);
        $this->attributes['content'] = $this->policyClean($value);
    }

    /**
     * Determines if the PolicyTemplate is required or optional for a given Business, based
     * on the Business type and the requirement setting in the PolicyTemplate.
     *
     * A template might be:
     * - required for all businesses
     * - required for one or more business types (e.g., Dental and/or Veterinary) and optional for others
     * - optional for all
     *
     * @param Business $business
     * @return string 'required' or 'optional'
     */
    public function getRequirement(Business $business)
    {
        $requirements = $this->requirement;
        $type = '';

        //check business specific
        if ($business->type == 'commercial') {
            $type = 'c';
        }

        //check business specific
        if ($business->type == 'medical') {
            $type = 'm';
        }

        //check business specific
        if ($business->type == 'dental') {
            $type = 'd';
        }

        //check business specific
        if ($business->type == 'veterinarian') {
            $type = 'v';
        }

        // check type specific, e.g., 'drequired' means 'required for dental businesses'
        if (in_array($type.'required', $requirements)) {
            return 'required';
        }

        // check type specific, e.g., 'doptional' means 'optional for dental businesses'
        if (in_array($type.'optional', $requirements)) {
            return 'optional';
        }

        // if there's no industry-specific
        if (in_array('required', $requirements)) {
            return 'required';
        }

        return 'optional';
    }

    /**
     * 
     */
    public function scopeEffective($query)
    {
        return $query->where('effective_date', '<=', Carbon::today());
    }
}
