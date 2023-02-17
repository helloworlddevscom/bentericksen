<?php

namespace App;

use App\BonusPro\MonthUser;
use App\Notifications\ActivationPasswordReset;
use App\Notifications\PasswordReset;
use Bentericksen\Permissions\Permissions;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User.
 *
 * Represents the user object.
 *
 * @property int $id The user's auto-numbered ID
 * @property int $business_id The ID of the business the user works for
 * @property int $classification_id The ID of the user's Classification (e.g., 'full time', 'part time', 'temporary')
 * @property string $email The user's email address
 * @property string $password The user's hashed password
 * @property string $remember_token
 * @property int $last_login The timestamp of the last time the user logged in
 * @property int $hired The timestamp of the date the user was hired
 * @property int $rehired The timestamp of the date the user was rehired
 * @property int $job_title_id The ID of the user's Job Title
 * @property int $dob The timestamp of the user's date of birth
 * @property int $age The user's age (unused)
 * @property string $status The user's status (e.g., 'enabled', 'disabled')
 * @property int $terminated The time stamp of when the user was terminated
 * @property bool $can_rehire Whether the user is eligible for re-hire
 * @property int $benefit_date (appears to be unused)
 * @property int $years_of_service (appears to be unused)
 * @property int $benefit_years_of_service (appears to be unused)
 * @property bool $on_leave (appears to be unused)
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $suffix
 * @property string $prefix
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $phone1
 * @property string $phone1_type
 * @property string $phone2
 * @property string $phone2_type
 * @property bool $included_in_employee_count (possibly unused)
 * @property bool $can_access_system
 * @property bool $receives_benefits
 * @property bool $employee_wizard
 * @property int $accepted_terms
 * @property int $created_at
 * @property int $updated_at
 * @property string $job_reports_to The name of the supervisor
 * @property string $job_location
 * @property string $job_department
 * @property string $employee_status The employment status, e.g., 'non-exempt', 'exempt'
 * @property string $position_title
 *
 * @property \App\Business $business The Business object representing the user's business
 * @property \Illuminate\Database\Eloquent\Collection<\App\Role> $roles Collection containing the list of Roles for the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\EmergencyContact> $emergencyContacts Collection containing the list of emergency contacts for the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\History> $history Collection containing the list of history records for the user
 * @property \App\Salary $salary The Salary object related to this user
 * @property \App\Classification $classification The Classification object representing parameters about the user's employment
 * @property \App\DriversLicense $driversLicense The DriversLicense object for the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\LicensureCertifications> Collection of LicensureCertifications for the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\JobDescription> $jobDescriptions Collection containing the list of job descriptions for the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\OutgoingEmail> $outgoingEmails Collection containing the list of emails sent to the user
 * @property \Illuminate\Database\Eloquent\Collection<\App\Attendance> $attendance
 * @property \Illuminate\Database\Eloquent\Collection<\App\TimeOff> $timeOff
 * @property \Illuminate\Database\Eloquent\Collection<\App\Paperwork> $paperwork
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable;
    use Authorizable, EntrustUserTrait {
        Authorizable::can insteadof EntrustUserTrait;
        EntrustUserTrait::can as entrustCan;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $guarded = ['main_role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates. See https://laravel.com/docs/5.7/eloquent-mutators#date-mutators.
     *
     * @var array
     */
    protected $dates = [
        'dob',
        'hired',
        'last_login',
        'bp_eligibility_date',
    ];

    protected $appends = ['main_role', 'bonuspro_only', 'bonuspro_enabled', 'full_name'];

    /**
     * Business Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    /**
     * User Role Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Emergency Contacts Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class, 'user_id');
    }

    /**
     * History (logs) relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(History::class);
    }

    /**
     * Salary Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salary()
    {
        return $this->hasOne(Salary::class, 'user_id');
    }

    /**
     * BonusPro Salary Data Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaryData()
    {
        return $this->hasMany(MonthUser::class, 'user_id');
    }

    /**
     * User Classification Relationship
     * NOTE: Business has many classification.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function classification()
    {
        return $this->hasOne(Classification::class, 'id', 'classification_id');
    }

    /**
     * Driver's License record relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function driversLicense()
    {
        return $this->hasOne(DriversLicense::class);
    }

    /**
     * Licensure / Certifications relationship
     * NOTE: Business has many classification.
     */
    public function licensures()
    {
        return $this->belongsToMany(LicensureCertifications::class, 'user_licensure_certifications')
            ->withTimestamps()
            ->withPivot('expiration');
    }

    /**
     * Job Description relationship
     * NOTE: Business has many job descriptions.
     */
    public function jobDescriptions()
    {
        return $this->belongsToMany(JobDescription::class, 'user_job_description');
    }

    /**
     * Outgoing Email relationship - emails that were sent to the user.
     */
    public function outgoingEmails()
    {
        return $this->hasMany(OutgoingEmail::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relationship to TimeOff records for the user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeOff()
    {
        return $this->hasMany(TimeOff::class)->orderBy('id', 'desc');
    }

    public function paperwork()
    {
        return $this->belongsToMany(Paperwork::class, 'users_paperwork');
    }

    /**
     * Get the User's Customer instance
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne('Bentericksen\Payment\Customers\Models\StripeCustomer');
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     *
     * @return bool
     * @todo Refactor this if/when we remove entrust in favor of newer Laravel permissions
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if (! is_array($name)) {
              return $role->name === $name;
            } else {
              return in_array($role->name, $name);
            }
        }

        return false;
    }

    /**
     * Calculates whether a user has a given permission.
     *
     * @param string $column  e.g., 'm240' or 'e120'
     * @return bool|string
     */
    public function permissions($column)
    {
        // admins, owners, and consultants get the highest possible permission for everything
        if ($this->hasRole(['admin', 'owner', 'consultant'])) {
            switch ($column) {
                case 'm120':
                    return 'View/Edit';
                case 'm180':
                    return 'Full Access';
                default:
                    return true;
            }
        }

        // the logic below applies to managers and employees

        $permissions = new Permissions($this->business_id, $column);

        $returnNumber = $permissions->getPermissions();

        if ($column === 'm120') {
            if ($returnNumber === 1) {
                return 'View/Edit';
            } elseif ($returnNumber === 2) {
                return 'View Only';
            }
        } elseif ($column === 'm180') {
            if ($returnNumber === 0) {
                return 'No Access';
            } elseif ($returnNumber === 1) {
                return 'Full Access';
            } elseif ($returnNumber === 2) {
                return 'Print Only';
            }
        } else {
            if ($returnNumber === 0) {
                return false;
            } elseif ($returnNumber === 1) {
                return true;
            }
        }
    }

    public function acl() {
      return $this->hasOne(BusinessPermission::class, 'business_id', 'business_id');
    }

    /**
     * Full name attribute accessor.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Hired date mutator.
     * @param $value
     *
     * @return Carbon
     */
    public function setHiredAttribute($value)
    {
        return $this->attributes['hired'] = Carbon::createFromTimestamp(strtotime($value));
    }

    /**
     * Date of birth mutator.
     * @param $value
     *
     * @return Carbon
     */
    public function setDobAttribute($value)
    {
        return $this->attributes['dob'] = Carbon::createFromTimestamp(strtotime($value));
    }

    /**
     * Phone 1 type attribute mutator.
     *
     * @param $value
     *
     * @return string
     */
    public function setPhone1TypeAttribute($value)
    {
        $this->attributes['phone1_type'] = strtolower($value);
    }

    /**
     * Salary Rate attribute mutator.
     *
     * @param $value
     *
     * @return string
     */
    public function setSalaryRateAttribute($value)
    {
        $this->attributes['salary_rate'] = strtolower($value);
    }

    /**
     * Phone 2 type attribute mutator.
     *
     * @param $value
     *
     * @return string
     */
    public function setPhone2TypeAttribute($value)
    {
        $this->attributes['phone2_type'] = strtolower($value);
    }

    /**
     * BP Eligibility Date mutator.
     * @param $value
     *
     * @return Carbon
     */
    public function setBpEligibilityDateAttribute($value)
    {
        $arr = explode('/', $value);
        array_splice($arr,1,0,['01']);
        $timestamp = implode('/', $arr);
        return $this->attributes['bp_eligibility_date'] = Carbon::createFromTimestamp(strtotime($timestamp));
    }

    /**
     * BP Eligibility Date Accessor.
     * @param $value
     * @return null|string
     */
    public function getBpEligibilityDateAttribute($value)
    {
        return $value ? Carbon::createFromTimestamp(strtotime($value))->format('m/Y') : null;
    }

    /**
     * Gets the employee's next anniversary date.
     * @return \Carbon\Carbon
     */
    public function getNextAnniversary()
    {
        if (! $this->hired) {
            return null;
        }

        $today = Carbon::today();
        $hired = Carbon::parse($this->hired);

        return $hired->addYears($hired->diffInYears($today) + 1);
    }

    /**
     * Gets the employee's years of tenure at their next anniversary date.
     * @return int
     */
    public function getTenure()
    {
        return $this->getNextAnniversary()->diffInYears($this->hired);
    }

    /**
     * Gets the employee's next birthday.
     * @return \Carbon\Carbon
     */
    public function getNextBirthday()
    {
        if (! $this->dob) {
            return null;
        }

        $today = Carbon::today();
        $dob = Carbon::parse($this->dob);

        return $dob->addYears($dob->diffInYears($today) + 1);
    }

    /**
     * Verifies if user can edit Policies.
     *
     * @return bool
     */
    public function canEdit()
    {
        $roles = $this->roles;

        // array of role names that can edit permissions.
        $roles_with_edit_permissions = ['admin', 'consultant'];

        $canEdit = false;

        foreach ($roles as $role) {
            if (in_array($role->name, $roles_with_edit_permissions)) {
                $canEdit = true;
                break;
            }
        }

        return $canEdit;
    }

    /**
     * Verifies if user can approve modified Policies.
     *
     * @return bool
     */
    public function canApprove()
    {
        $roles = $this->roles;

        // array of role names that can approve modifications.
        $roles_with_approve_permissions = ['admin', 'consultant'];

        // Deny access to consultants if business is finalized.
        $view_as = new ViewAs();
        if ($view_as->getUserId() != $this->id) {
            $view_user = self::findOrFail($view_as->getUserId());
            if ($view_user->business->finalized) {
                $roles_with_approve_permissions = ['admin'];
            }
        }

        $canApprove = false;

        foreach ($roles as $role) {
            if (in_array($role->name, $roles_with_approve_permissions)) {
                $canApprove = true;
                break;
            }
        }

        return $canApprove;
    }

    /**
     * Return primary role for user entity.
     *
     * @return string
     */
    public function getRole()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            array_push($roles, $role->name);
        }

        if (in_array(Role::CONSULTANT, $roles)) {
            return Role::CONSULTANT;
        }

        if (in_array(Role::ADMIN, $roles)) {
            return Role::ADMIN;
        }

        if (in_array(Role::MANAGER, $roles)) {
            return Role::MANAGER;
        }

        if (in_array(Role::OWNER, $roles)) {
            return Role::OWNER;
        }

        if (in_array(Role::EMPLOYEE, $roles)) {
            return Role::EMPLOYEE;
        }

        return Role::CLIENT;
    }

    public function getRoleNames()
    {
        return $this->roles->map(function ($role) {
            return $role->name;
        });
    }

    public function getMainRoleAttribute()
    {
        return $this->getRole();
    }

    public function getBonusproEnabledAttribute() {
        return $this->business &&
            $this->business->bonuspro_enabled;
    }

    public function getBonusproOnlyAttribute()
    {
        return $this->business &&
            $this->business->bonuspro_enabled &&
            ! $this->business->hrdirector_enabled;
    }

    public function getShowSopAttribute()
    {
        return $this->business && $this->business->type == 'dental';
    }

    public function getSopEnabledAttribute()
    {
        return $this->show_sop &&
        ($this->business->status === 'active' || $this->business->status === 'renewed') &&
        $this->business->type == 'dental' &&
        $this->business->hrdirector_enabled &&
        $this->business->enable_sop;
    }

    /**
     * Determine Payment Access Form for subscriptions
     * @return bool
     */
    public function getPaymentAccess()
    {
        $view_as = new ViewAs();
        $viewUser = $view_as->getActualUser()->getRole();

        if(($this->hasRole('manager') && $this->permissions(('m280'))) ||
            ($this->hasRole('owner') || $this->hasRole('admin'))
            && ($viewUser !== 'consultant')) {
                return 'full';
            } else if ($viewUser === 'consultant') {
                return 'view';
            }
        return 'none';
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        if (session('activation_password_reset')) {
            $this->notify(new ActivationPasswordReset($token, $this));
            session(['activation_password_reset' => null]);
        } else {
            $this->notify(new PasswordReset($token, $this));
        }
    }

    public function addLicensures($data)
    {
        $userData = [];
        foreach ($data as $row) {
            if ($row['licensure_certifications_id'] == 'new') {
                $row['status'] = 'active';
                $licensure = $this->business->licensures()->create($row);
            } else {
                $licensure = LicensureCertifications::find($row['licensure_certifications_id']);
            }

            array_push($userData, [
                'licensure_certifications_id' => isset($licensure->id) ? $licensure->id : '',
                'expiration' => Carbon::createFromTimestamp(strtotime($row['expiration'])),
            ]);
        }

        if (! empty($userData)) {
            $this->licensures()->attach($userData);
        }
    }

    public function updateLicensures($data)
    {
        foreach ($data as $id => $expiration) {
            $this->licensures()->updateExistingPivot($id, [
                'expiration' => Carbon::createFromTimestamp(strtotime($expiration)),
            ]);
        }
    }

    public function removeLicensures($data)
    {
        $this->licensures()->detach($data);
    }

    /**
     * Adding history log entry. * SEE AppServiceProvider.
     *
     * @param $type
     * @param array $updatedAttributes
     */
    public function createHistoryLog($type, array $updatedAttributes = [])
    {
        // Adding entries.
        if (empty($updatedAttributes)) {
            return;
        }

        // Combine the reason note with the salary note for salary updates
        if ($type == 'Salary') {
            $reasonKey = array_search('reason', array_column($updatedAttributes, 'attr'));
            if ($reasonKey != false) {
                $reason = $updatedAttributes[$reasonKey]['new'];
                $salaryKey = array_search('salary', array_column($updatedAttributes, 'attr'));
                if ($salaryKey != false) {
                    // include the reason as part of the note for the salary update
                    $updatedAttributes[$salaryKey]['new'] .= ". Reason for update: $reason";
                    unset($updatedAttributes[$reasonKey]);
                }
            }
        }

        foreach ($updatedAttributes as $data) {
            $this->history()->save(new History([
                'type' => $type,
                'business_id' => $this->business_id,
                'note' => $data['attr'].' updated from '.$data['current'].' to '.$data['new'],
                'status' => 'active',
            ]));
        }
    }

    /**
     * Constraint for only hired users.
     */
    public function scopeHired($query)
    {
        return $query->where('terminated', null);
    }

    /**
     * Get the Streamdent User associated with the business
     *
     * @return StreamdentUser
     */
    public function streamdentUser()
    {
        return $this->hasOne('App\StreamdentUser');
    }

    public function scopeOwner($query)
    {
        return $query->join('role_user', function($join) {
            $join->on('role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', 2);
        });
    }

    public function scopeManager($query)
    {
        return $query->join('role_user', function($join) {
            $join->on('role_user.user_id', '=', 'users.id')
                ->where('role_user.role_id', 3);
        });
    }

    public function streamdentJobPending()
    {
        $pending = false;
        foreach (DB::table('jobs')->pluck('payload') as $record) {
            $payload = json_decode($record);
            $commandName = $payload->data->commandName;
            $commandData = unserialize($payload->data->command);
            if (!stripos($commandName, "PerformStreamdentUserProcess") ||
                    $commandData->getProcess() !== "create") {
                continue;
            }
            if (method_exists($commandName, 'getUser') && $commandData->getUser()->id !== $this->id) {
                continue;
            }
            $pending = true;
            break;
        }
        return $pending;
    }

    public function streamdentJobFailure()
    {
        $error = 'Streamdent: No associated streamdent user found';
        foreach (DB::table('failed_jobs')->select('id', 'payload')->get() as $record) {
            $jobId = $record->id;
            $payload = json_decode($record->payload);
            $commandName = $payload->data->commandName;
            $commandData = unserialize($payload->data->command);
            if (!stripos($commandName, "PerformStreamdentUserProcess") || $commandData->getProcess() !== "create") {
                continue;
            }
            if ($commandData->getUser()->id !== $this->id) {
                continue;
            }
            $error = sprintf('Streamdent: Account setup failed (process id: %s).', $jobId);
            break;
        }
        return $error;
    }
    
    public function policyUpdates()
    {
      return $this->hasMany(UserPolicyUpdates::class)->accepted();
    }

    public function getNeedsPolicyManualRegenerationAttribute()
    {
      $lastPolicy = $this->policyUpdates->last();
      $business = $this->business;

      if (empty($lastPolicy) || empty($business)) {
        return false;
      }

      return $lastPolicy->accepted_at > $business->manual_created_at;
    }
}
