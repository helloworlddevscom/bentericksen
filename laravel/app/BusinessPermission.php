<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BusinessPermission.
 *
 * ORM model for the business permissions. These determine what each user type
 * can do within the system.
 *
 * @property int $id
 * @property int $business_id
 * @property int $m100  Can Managers Access the HR Director?
 * @property int $m101  Can Managers Access BonusPro?
 * @property int $m120  Policy Manual And Policy List
 * @property int $m121  Policy Update Process
 * @property int $m140  Basic Employee Info
 * @property int $m144  Can Approve/Deny Time Off Requests
 * @property int $m145  Can Approve/Deny Leave of Absence Requests
 * @property int $m148  Can View History Events, Add Notes to History
 * @property int $m160  View/Edit list of saved job descriptions, Assign to employees
 * @property int $m180  View/Edit/Print All Forms
 * @property int $m200  Can Access the HR FAQ's
 * @property int $m201  Can Use Calculators
 * @property int $m240  Can View/Edit the Company Dashboard
 * @property int $m260  Can View/Edit Company Account Info
 * @property int $m280  Can update and change payment information
 * @property int $m300  Can edit SOPs
 * @property int $e100  Can Employees Access the HR Director?
 * @property int $e120  Can View the latest Policy Manual
 * @property int $e160  Can View their Current Job Description
 * @property int $e200  Can Request Time Off/Leave of Absence
 * @property int $e221  Can Edit Personal Contact Info
 * @property int $e230  Can View SOPs
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @see \Bentericksen\Models\Permissions\Permissions
 * @see \App\Http\Controllers\User\PermissionController
 */
class BusinessPermission extends Model
{
    protected $table = 'business_permissions';

    protected $guarded = [];

    protected $fillable = [
        'business_id',
        'm100',
        'm101',
        'm120',
        'm121',
        'm140',
        'm144',
        'm145',
        'm148',
        'm160',
        'm180',
        'm200',
        'm201',
        'm240',
        'm260',
        'm280',
        'm300',
        'e100',
        'e120',
        'e160',
        'e200',
        'e221',
        'e230',
    ];

    /**
     * Business relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function getColumns()
    {
        return $this->fillable;
    }
}
