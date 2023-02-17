<?php

namespace App;

use Bentericksen\Traits\HTMLPolicyCleanup;
use Bentericksen\Traits\WYSIWYGSpaceStrip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Policy.
 *
 * Represents a copy of a policy customized for a specific Business.
 *
 * @property int $id
 * @property int $business_id
 * @property int $category_id
 * @property int $template_id
 * @property string $manual_name
 * @property string $content
 * @property string $content_raw
 * @property string $status  Whether it's 'enabled' or 'disabled'.
 * @property int $order  The sort order in the list
 * @property string $requirement  Whether the policy is required for all businesses, or optional.
 *   Valid values: 'required', 'optional'
 * @property array $tags
 * @property string $edited
 * @property bool $is_modified
 * @property string $special  A special flag for this policy, e.g., "stub"
 * @property string $special_extra  A JSON object with info for the "special" flag, encoded as a string
 * @property bool $include_in_benefits_summary
 *
 * @property \App\Business $business The specific business to which the Policy applies
 * @property \App\PolicyTemplate $template The global template on which this policy is based
 */
class Policy extends Model
{
    use HTMLPolicyCleanup;
    use WYSIWYGSpaceStrip;
    use SoftDeletes;

    protected $table = 'policies';

    protected $guarded = [];

    /**
     * PolicyTemplate relation.
     */
    public function template()
    {
        return $this->belongsTo(\App\PolicyTemplate::class);
    }

    /**
     * Business Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    /**
     * Manual Name Mutator.
     * @param $value
     */
    public function setManualNameAttribute($value)
    {
        $this->attributes['manual_name'] = strtoupper($value);
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
     * Mutator to cleanup/remove inline formatting from CKEditor from content_raw.
     * @param $value
     */
    public function setContentRawAttribute($value)
    {
        $value = $this->stripSpaces($value);
        $this->attributes['content_raw'] = $this->policyClean($value);
    }

    /**
     * Checks whether the current user can edit a policy's title.
     *
     * If it's required, ONLY admins can edit.
     * If it's optional, the user has to have any of these roles: [admin, consultant, manager, owner].
     *
     * @return bool
     */
    public function userCanEdit(User $user)
    {
        if ($this->requirement === 'required') {
            return $user->hasRole('admin');
        }

        return $user->hasRole(['admin', 'consultant', 'manager', 'owner']);
    }
}
