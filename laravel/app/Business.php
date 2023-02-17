<?php

namespace App;

use App\BonusPro\Plan;
use Bentericksen\Policy\BusinessPoliciesManager;
use Bentericksen\Policy\PolicyRules;
use App\Facades\StreamdentService as StreamdentService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Bentericksen\Payment\Subscriptions\Models\StripeSubscription;
use Bentericksen\Payment\PaymentService as PayService;

/**
 * Class Business.
 *
 *
 * @property int $id
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $phone1
 * @property string $phone1_type
 * @property string $phone2
 * @property string $phone2_type
 * @property string $phone3
 * @property string $phone3_type
 * @property string $fax
 * @property string $website
 * @property int $primary_user_id
 * @property string $secondary_1_first_name
 * @property string $secondary_1_middle_name
 * @property string $secondary_1_last_name
 * @property string $secondary_1_prefix
 * @property string $secondary_1_suffix
 * @property string $secondary_1_email
 * @property string $secondary_1_role
 * @property string $secondary_2_first_name
 * @property string $secondary_2_middle_name
 * @property string $secondary_2_last_name
 * @property string $secondary_2_prefix
 * @property string $secondary_2_suffix
 * @property string $secondary_2_email
 * @property string $secondary_2_role
 * @property string $type
 * @property string $subtype
 * @property int $consultant_user_id
 * @property bool $ongoing_consultant_cc
 * @property string $status
 * @property int $asa_id
 * @property bool $do_not_contact
 * @property string $referral
 * @property int $additional_employees
 * @property bool $employee_count_reminder
 * @property string $manual
 * @property int $created_at
 * @property int $updated_at
 * @property int $manual_create_at
 * @property bool $enable_sop
 * @property bool $finalized
 * @property int $streamdent_name_increment
 *
 * @property \Illuminate\Database\Eloquent\Collection<\App\User> $users
 * @property \App\BusinessPermission $permissions
 * @property \App\BusinessAsas $asa
 * @property \Illuminate\Database\Eloquent\Collection<\App\Policy> $policies
 * @property \Illuminate\Database\Eloquent\Collection<\App\BusinessSettings> $settings
 * @property \Illuminate\Database\Eloquent\Collection<\App\Form> $forms
 * @property \Illuminate\Database\Eloquent\Collection<\App\Classification> $classifications
 * @property \Illuminate\Database\Eloquent\Collection<\App\LicensureCertifications> $licensures
 */
class Business extends Model
{
    protected $table = 'business';

    protected $guarded = [];

    protected $dates = [
        'manual_created_at',
        'bonuspro_expiration_date',
    ];

    protected $appends = [
      'sop_active',
      'default_card_info'
    ];

    /**
     * Get the users/employees that belong to this business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\User::class);
    }

    public function contactUser()
    {
        return $this->hasOne(\App\User::class, 'id', 'primary_user_id');
    }

    /**
     * Get the hired users/employees that belong to this business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hiredUsers()
    {
        return $this->hasMany(\App\User::class)->hired();
    }

    /**
     * Business permissions relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function permissions()
    {
        return $this->hasOne(BusinessPermission::class);
    }

    /**
     * Asas Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function asa()
    {
        return $this->hasOne(BusinessAsas::class);
    }

    /**
     * Policies relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }

    /**
     * Settings relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(BusinessSettings::class);
    }

    /**
     * Forms relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forms()
    {
        return $this->hasMany(Form::class, 'business_id');
    }

    /**
     * Classifications relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classifications()
    {
        return $this->hasMany(Classification::class, 'business_id');
    }

    /**
     * Certifications relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licensures()
    {
        return $this->hasMany(LicensureCertifications::class);
    }

    /**
     * BonusPro plans relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Get the Business' StripeSubscription instance
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stripeSubscription()
    {
        return $this->hasOne('Bentericksen\Payment\Subscriptions\Models\StripeSubscription');
    }

    /**
     * Update Business settings.
     *
     * @param $settings
     */
    public function updateSettings($settings)
    {
        foreach ($settings as $type => $value) {
            BusinessSettings::updateOrCreate(['business_id' => $this->id, 'type' => $type], [
                'business_id' => $this->id,
                'type' => $type,
                'value' => $value,
            ]);
        }
    }

    /**
     * Returns all the business settings in an Object.
     * @return \stdClass
     */
    public function getSettings()
    {
        $settings = new \stdClass();

        // todo: implement default values.
        $settings->dashboard_reminders_days = 60;

        if (count($this->settings) > 0) {
            foreach ($this->settings as $setting) {
                $settings->{$setting->type} = $setting->value;
            }
        }

        return $settings;
    }

    /**
     * Returns User instance of assigned Consultant.
     *
     * @return User|null
     * @todo This could be an ORM relationship
     */
    public function getConsultant()
    {
        return User::find($this->consultant_user_id);
    }

    /**
     * Returns an array with name and email of all users attached to the business
     * for communication purposes.
     *
     * @return array
     */
    public function getBusinessUsers()
    {
        $users = [];
        array_push($users, $this->getPrimaryUser());
        $users = array_merge($users, $this->getSecondaryUsersInfo());

        return $users;
    }

    /**
     * Returns primary user's info.
     *
     * @param bool $return_object true for returning Model instance.
     *
     * @return array|User
     *   Either an associative array containing the name and email address,
     *   or a User object (depending on the value of $return_object)
     */
    public function getPrimaryUser($return_object = false)
    {
        $primary_user = User::find($this->primary_user_id);

        return $return_object ? $primary_user : [
            'id' => $primary_user->id,
            'full_name' => $primary_user->fullname,
            'email' => $primary_user->email,
        ];
    }

    /**
     * Returns manager users info.
     *
     * @param int The Business ID
     *
     * @return array
     *   an associative array of users with the manager role.
     */
    public function getManagers()
    {
        $managers = [];
        $users = User::where('business_id', $this->id)
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->get();

        foreach ($users as $user) {
            if ($user['role_id'] == 3) {
                array_push($managers, $user);
            }
        }

        return $managers;
    }

    public function getNonPrimaryOwners($returnObjects = false)
    {
        $users = User::where('business_id', '=', $this->id)
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('id', '!=', $this->primary_user_id)
            ->where('role_user.role_id', '=', 2)
            ->get();
        return $users->each(function($value, $key) use($returnObjects) {
            return $returnObjects === true ? $value : [
                'id' => $value->id,
                'full_name' => $value->first_name . ' ' . $value->last_name,
                'email' => $value->email
            ];
        });
    }

    /**
     * Returns secondary users info.
     * Note: Made this public for unit tests - KD.
     *
     * @return array
     *   An array of associative arrays containing the name and email address ese
     *   of the secondary contact people, e.g.,
     *   [
     *     ['full_name' => 'Alice', 'email' => 'alice@example.com'],
     *     ['full_name' => 'Bob', 'email' => 'bob@example.com'],
     *   ]
     */
    public function getSecondaryUsersInfo()
    {
        $users = [];

        if ($this->secondary_1_email) {
            $user = [
                'email' => $this->secondary_1_email,
            ];

            $user['full_name'] = $this->secondary_1_first_name.' '.$this->secondary_1_last_name;

            array_push($users, $user);
        }

        if ($this->secondary_2_email) {
            $user = [
                'email' => $this->secondary_2_email,
            ];

            $user['full_name'] = $this->secondary_2_first_name.' '.$this->secondary_2_last_name;

            array_push($users, $user);
        }

        return $users;
    }

    /**
     * Contact name attribute accessor.
     *
     * @return string
     */
    public function getContactNameAttribute()
    {
        return $this->contactUser->fullName;
    }

    public function getIsBonusProEnabledAttribute()
    {
        return $this->bonuspro_enabled &&
        (
            $this->bonuspro_lifetime_access ||
            strtotime($this->bonuspro_expiration_date) > strtotime('now')
        );
    }

    /**
     * Computed property for telling if a business has SOPs enabled
     *
     * @return bool
     */
    public function getSopActiveAttribute(): bool
    {
      return $this->hrdirector_enabled && $this->enable_sop && $this->active_business;
    }

    /**
     * BonusPro Expiration Date mutator.
     * @param $value
     *
     * @return Carbon
     */
    public function setBonusproExpirationDateAttribute($value)
    {
        return $this->attributes['bonuspro_expiration_date'] = $value ? Carbon::createFromTimestamp(strtotime($value)) : null;
    }

    /**
     * Gets the policies for the business, sorted by category and order.
     *
     * @param bool $enabled_only
     *   True to return only enabled Policies (as when printing the manual) or false to return all Policies that are
     *   not closed stubs.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSortedPolicies($enabled_only = true)
    {
        $policies = Policy::where('business_id', $this->id);
        if ($enabled_only) {
            $policies->where('status', '=', 'enabled');
        } else {
            $policies->where('status', '!=', 'closed');
        }

        return $policies->orderBy('category_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Updates the list of Policies for the Business, adding new Policies that have recently begun to affect the
     * Business, and removing Policies that no longer affect the Business.
     *
     * @param bool $fullReset  Whether to delete all existing policies and reset to the templates
     * @see BusinessPoliciesManager
     */
    public function updatePolicies($fullReset = false)
    {
        $rules = new PolicyRules($this);
        $policiesMgr = new BusinessPoliciesManager($this, $rules, $fullReset);
        $policiesMgr->update();
    }

    /**
     * Checks if business has policies that have been modified.
     *
     * @return bool
     */
    public function hasPendingPolicies()
    {
        $policy_count = Policy::where('business_id', $this->id)
            ->where('status', 'pending')
            ->count();

        return $policy_count > 0;
    }

    /**
     * Checks whether the consultant should receive notifications or not (ongoing_consultant_cc flag).
     * @return bool
     */
    public function notifyConsultant()
    {
        if (! $this->finalized) {
            return true;
        }

        return $this->ongoing_consultant_cc;
    }

    /**
     * Updates Business status.
     *
     * @param $status string
     *
     * @return bool
     */
    public function updateStatus($status)
    {
        $this->status = $status;

        return $this->save();
    }

    /**
     * Returns whether the primary user approved terms and conditions.
     *
     * @return bool
     */
    public function termsAccepted()
    {
        $business_primary_user = $this->getPrimaryUser(true);
        $managers = $this->getManagers();
        array_push($managers, $business_primary_user);
        $owners = $this->getNonPrimaryOwners(true);
        foreach($owners as $owner) {
            array_push($managers, $owner);
        }
        if (count($managers) > 1 && $business_primary_user->accepted_terms == '0000-00-00 00:00:00') {
            foreach ($managers as $manager) {
                if ($manager['accepted_terms'] != '0000-00-00 00:00:00') {
                    return $manager['accepted_terms'] !== '0000-00-00 00:00:00';
                } else {
                    // do nothing
                }
            }
        } else {
            return $business_primary_user->accepted_terms !== '0000-00-00 00:00:00';
        }
    }

    /**
     * Checks eligibility for a business to receive the policy update emails.
     *
     * @param $inactive_date
     *
     * @return bool
     */
    public function isEligibleForUpdateNotifications($inactive_date)
    {
        // array of eligible status to receive policy updates notifications.
        $eligible_statuses = ['active', 'expired', 'renewed'];

        // If business is flagged with do-not-contact, not eligible.
        if ($this->do_not_contact == 1) {
            return false;
        }

        // If business type is "Bonus Pro Only", not eligible.
        if (! in_array(strtolower($this->status), $eligible_statuses)) {
            return false;
        }

        // If Business does not have an ASA set, or
        // ASA expiration date is not set, or
        // ASA expiration date is prior the inactive date provided by
        // the Policy Updater object, the Business is not eligible.
        // ** SEE PoliciesController@updatesCreate **
        if (! $this->asa || ! $this->asa->expiration || $this->asa->expiration->lt($inactive_date)) {
            return false;
        }

        return true;
    }

    /**
     * Gets a simplified version of the business status, used in the
     * Policy Update Email process.
     * @return string either 'active' (= 'active' and 'renewed') or 'inactive'
     */
    public function getStatusForUpdateEmails()
    {
        if ($this->status === 'active' || $this->status === 'renewed') {
            return 'active';
        }

        return 'inactive';
    }

    /**
     * business status attribute accessor.
     * Gets a simplified version of the business status, used generically
     * to identify is a business is active or notice
     *
     * @param $status string
     *
     * @return boolean
     *  'true' (= 'active' and 'renewed') or
     *  'false (= 'expired' or 'cancelled')
     */

    public function getActiveBusinessAttribute()
    {
        return $this->status === 'active' || $this->status === 'renewed';
    }

    /**
     * Switches current primary user. Used in the Edit Business Form.
     *
     * @param $newPrimaryUser
     *
     * @return $this
     */
    public function switchPrimaryUser($newPrimaryUser)
    {
        $currentUser = $this->getPrimaryUser(true);
        $currentUser->roles()->detach(2);
        $currentUser->roles()->attach(5);

        $user = User::find($newPrimaryUser);

        if ($user->hasRole('owner')) {
            //
        } else {
            $currentUser->roles()->detach(5);
            $currentUser->roles()->attach(2);
        }

        return $this;
    }

    public function getLicensureOptions()
    {
        if ($this->type !== 'dental') {
            return $this->licensures;
        }

        return LicensureCertifications::whereIn('business_id', ['0', $this->id])->get();
    }

    /**
     * Renew BusinessASA
     * @param $asaData
     * @return bool
     */
    public function renewASA($asaData) {

        $asaData['type'] = str_replace(" ", "-", strtolower($asaData['type']));
        return $this->asa()->update($asaData);

    }

    /**
     * Enable Business' Users
     */
    public function enableUsers() {
        $users = $this->users;
        foreach($users as $user) {
            $user->update(['status' => 'enabled']);
        }
    }

    /**
     * A Business can subscribe if it's 'status' is expired or
     * if hasn't yet created a StripeSubscription
     * @return bool
     */
    public function canSubscribe() {

        if($this->status === "expired" || !$this->hasStripeSubscription()) {
            return true;
        }

        return false;

    }

    /**
     * @return bool
     */
    public function hasStripeSubscription() {
        return StripeSubscription::where('business_id', $this->id)->exists();
    }

    /**
     * @return StripeSubscription
     */
    public function getStripeSubscription() {
        return StripeSubscription::where('business_id', $this->id)->first();
    }

    /**
     * @return bool
     */
    public function isRenewal() {
        return $this->status === "expired";
    }

    /**
     * @return bool
     */
    public function canStripeSmartRetry($currentStatus) {
        return $currentStatus === "expired" && $this->asa->expiration > Carbon::now()->subDays(14);
     }

    /**
     * Determines whether the one-time fee should be applied to purchase
     * @return bool
     */
    public function applyOneTimeFee() {

        $threshold = Carbon::create(2021, 1, 28, 0);
        return $this->status === "active"
            && !$this->hasStripeSubscription()
            && is_null($this->payment_type)
            && ($this->created_at > $threshold);

    }

    /**
     * Get the StreamdentClient associated with the business
     *
     * @return StreamdentClient
     */
    public function streamdentClient()
    {
        return $this->hasOne('App\StreamdentClient');
    }

    /**
     * Get the StreamdentClient associated with the business
     *
     * @return StreamdentClient
     */
    public function streamdentUsers()
    {
        return $this->hasMany('App\StreamdentUSer');
    }

    /**
     * @param $paymentType
     * @return void
     */
    public function setPaymentType($paymentType) {
        $this->payment_type = $paymentType;
        $this->save();
    }

    public function getDefaultCardInfoAttribute() {

      $user = $this->getPrimaryUser(true);

      if (empty($user)) {
        return null;
      }

      $userPaymentAccess = $user->getPaymentAccess();

      return (object) $userPaymentAccess === 'full' ? 
        PayService::getDefaultCardInfo($user) :
        null;
    }

}
