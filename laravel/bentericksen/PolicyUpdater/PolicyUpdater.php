<?php

namespace Bentericksen\PolicyUpdater;

use Carbon\Carbon;
use App\Business;
use App\PolicyTemplate;
use App\PolicyTemplateUpdate;
use App\PolicyUpdater as PolicyUpdateModel;
use App\Role;
use App\User;
use Bentericksen\Policy\PolicyRules;
use mysql_xdevapi\Exception;

/**
 * Class PolicyUpdater
 *
 * A utility class for managing the data in the Policy Update List form.
 * This is a wrapper around the ORM model with a similar name.
 * @see \App\PolicyUpdater
 * @package Bentericksen\PolicyUpdater
 */
class PolicyUpdater
{

    protected $id;

    /**
     * @var \App\PolicyTemplateUpdate[]
     *   An array of PolicyTemplateUpdate ORM objects
     */
    protected $available_policies;

    protected $steps = [
        '1' => '_step1',
        '2' => '_step2',
        '3' => '_step3',
        '4' => '_step4',
    ];

    private $view;

    /**
     * @var int What step the form is on (1-4)
     */
    private $step;

    /**
     * @var \App\PolicyUpdater
     *   The Updater ORM model. Mostly used for data storage and ORM relationships.
     *   It contains very little logic.
     */
    private $update;

    /**
     * @var array  Additional email addresses for this updater, as an array of strings
     */
    private $additional_emails;

    private $next;

    public function __construct()
    {
        $this->available_policies = PolicyTemplateUpdate::where('effective_date', '>', Carbon::now())
            ->where('processed', false)
            ->get();
    }

    /**
     * Creates a new Policy Update row and populates it into $this->update.
     *
     * @return $this
     */
    public function create()
    {
        $this->update = new PolicyUpdateModel;
        $this->update->step = 1;
        $this->update->status = 'pending';
        $this->update->save();

        $this->id = $this->update->id;
        $this->view = $this->steps[1];

        $this->setChecked();

        return $this;
    }

    /**
     * Loads an existing PolicyUpdater. This is a wrapper around the ORM's
     * find() method.
     * @param array $data
     *   An associative array containing params from the HTTP request.
     *   Notably, contains 'step' and 'id' keys.
     *
     * @return $this
     */
    public function load($data)
    {
        $this->step = $data['step'];
        $this->update = PolicyUpdateModel::find($data['id']);
        $this->updateData($data);
        $this->id = $this->update->id;

        $this->setAdditionalEmails();
        $this->setNext();
        $this->setStep();
        $this->setChecked();

        return $this;
    }

    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    private function setAdditionalEmails()
    {
        $this->additional_emails = $this->update->contacts()->additional();

        if (empty($this->additional_emails)) {
            $this->additional_emails = [];
        }
    }

    private function setNext()
    {
        $next = (int) $this->step + 1;
        if ($next > count($this->steps)) {
            $next = "finalize";
        }

        $this->next = $next;
    }

    /**
     * Updates some data on the inner "update" object. Sort of a mass assignment.
     * @param array $data  An associative array of values to set
     *
     * @return $this
     */
    private function updateData($data)
    {
        $jsonInputs = [
            'policies',
            'faqs',
            'job_descriptions',
        ];

        foreach ($data as $key => $value) {
            if ($key == "emails") {
                $value = array_map(function($email) {
                    return [
                        'email' => trim($email)
                    ];
                }, $value);
                $this->update->contacts()->createMany($value);
            }

            if ($key == "additional_emails") {
                $temp = explode(PHP_EOL, $value);
                $value = array_map(function($email) {
                    return [
                        'email' => trim($email),
                        'contact_type' => 'additional'
                    ];
                }, $temp);
                $this->update->contacts()->createMany($value);
            }

            if ($key == "start_date") {
                $date = Carbon::createFromFormat('m/d/Y', $value);
                $value = $date->format('Y-m-d') . " 00:00:00";
            }

            if (!in_array($key, ['emails', 'additional_emails'])) {
                $this->update->$key = is_array($value) && in_array($key, $jsonInputs) ? json_encode($value) : $value;
            }
        }

        $this->update->save();

        return $this;
    }

    private function setChecked()
    {
        if (!is_null($this->update->policies)) {
            $policies = json_decode($this->update->policies, true);

            foreach ($this->available_policies as &$policy) {
                if (in_array($policy->id, $policies)) {
                    $policy->checked = 1;
                }
            }
        }
    }


    private function setStep()
    {
        if ($this->step == "finalize") {
            $this->view = $this->steps[$this->step];
            return true;
        }

        if (is_int($this->next) && $this->next >= 1) {
            $this->view = $this->steps[$this->next - 1];
        }
        if (is_null($this->view)) {
            $this->view = $this->steps[$this->step];
        }
    }

    /**
     * Returns the email lists for this updater
     * @param integer $inactive_clients_years
     *   A time span in years. Emails will go to inactive clients who have been
     *   inactive for less than this number of years
     * @return array Associative array containing sub-arrays for 'list' and 'other_list',
     * which each contain email addresses.
     */
    public function getEmailLists($inactive_clients_years)
    {
        // The list of people who the email will be addressed to, by default
        // (except some people from inactive clients, who get unselected by
        // default in the JavaScript in _step2.blade.php)
        $list = [];
        // the list of all other people, who are all deselected by default
        // and can be added manually
        $other_list = [];

        // Sometimes we notify inactive businesses of the updates, based on the
        // number of years they've been inactive. Calculate the start date of
        // that range, based on parameters entered on the form.
        $inactive_clients_setting = $this->update->inactive_clients;
        if (!is_null($inactive_clients_years)) {
            $inactive_clients_setting = $inactive_clients_years;
        }
        $inactive_date = Carbon::now()->addYears(-$inactive_clients_setting);

        $policies_updated = json_decode($this->update->policies);
        $policy_update_ids = array_keys((array)$policies_updated);

        $businesses = Business::with('asa')->get();
        $updated_templates = PolicyTemplate::whereIn('id', $policy_update_ids)->get();

        // 1. Get a list of the businesses affected by this update
        $notify_businesses = [];
        foreach ($updated_templates as $template) {
            foreach ($businesses as $business) {
                // check if policy update applies for the business
                $rules = new PolicyRules($business);
                if ($rules->all($template) === true) {
                    $notify_businesses[] = $business->id;
                }
            }
        }
        // Note: $notify_businesses now contains the list of businesses eligible to
        // receive the email, based on state, employee count, and effective date.
        // It does not check to see if the business actually is using the policies
        // that were updated. (Though usually they will be.)

        // 2. Build the list of users based on the businesses
        // discovered above.
        $primary_contacts = [];
        $do_not_contact = [];  // businesses we shouldn't contact, due to status or "do not contact" flag
        $consultants_added = [];  // list of consultants receiving email
        $business_statuses = [];
        foreach ($businesses as $business) {

            if (!$business->isEligibleForUpdateNotifications($inactive_date)) {
                $do_not_contact[] = $business->id;
                continue;
            }

            // business status is used in the list of "other" potential recipients below
            // the 'status' array key determines which email template they get
            $status = $business->getStatusForUpdateEmails();
            $business_statuses[$business->id] = $status;

            if (in_array($business->id, $notify_businesses)) {
                $primary_contacts[] = $business->primary_user_id;

                // Add secondary email (1)
                if (!empty($business->secondary_1_email)) {
                    $list[] = [
                        'role' => 'secondary',
                        'status' => $status,
                        'email' => $business->secondary_1_email,
                    ];
                }

                // Add secondary email (2)
                if (!empty($business->secondary_2_email)) {
                    $list[] = [
                        'role' => 'secondary',
                        'status' => $status,
                        'email' => $business->secondary_2_email,
                    ];
                }

                // Add consultants
                // (we do this here rather than in the users loop below, so
                // they get their client's business ID and "do not contact"
                // status, instead of their own business ID and status
                // FIXME: a consultant might be associated with both active and inactive business
                // Only Active consultants receive emails.
                if (!empty($business->consultant_user_id)
                    && $business->notifyConsultant()
                    && !in_array($business->consultant_user_id, $consultants_added)
                    && $status == 'active') {
                    
                    $consultant = $business->getConsultant();
                    $list[] = [
                        'role' => 'consultant',
                        'status' => $status,
                        'email' => $consultant->email
                    ];
                    $consultants_added[] = $consultant->id;  // this var is only for de-duping
                }
            }
        }

        // 3. loop through all the users and determine if they should receive an
        // email, based on user role and permissions (e.g., owners, managers)
        $users = User::with('roles')
            // Skip any Consultants we already added above
            ->whereNotIn('id', $consultants_added)
            ->whereNotIn('business_id', $do_not_contact)
            // we can't calculate status without a business id, so skip those
            ->whereNotNull('business_id')
            ->get();
        foreach ($users as $user) {

            // Checking business permissions for managers. If user is a manager and
            // has permissions to accept updates, add to the 'primary contacts' list.
            if ($user->hasRole('manager') && $user->permissions('m121')) {
                $primary_contacts[] = $user->id;
            }
            
            if ($user->hasRole('owner')) {
                $primary_contacts[] = $user->id;
            }


            if (in_array($user->id, $primary_contacts)) {
                // Primary contacts go into the "selected" list
                $info = [
                    'role' => 'primary',
                    'status' => $business_statuses[$user->business_id],
                    'email' => $user->email,
                ];
                if (in_array($user->business_id, $notify_businesses)) {
                    $list[] = $info;
                } else {
                    $other_list[] = $info;
                }
            } else {
                // All other users go into the "available" list. These people
                // *could* receive an email if the admin decides to add them
                // using the widgets on the form.
                $other_list[] = [
                    'role' => $user->hasRole('manager') ? 'manager' : $user->getRole(),
                    'status' => $business_statuses[$user->business_id],
                    'email' => $user->email,
                ];
            }
        }

        return ['list' => $list, 'other_list' => $other_list];
    }

    public function finalize()
    {
        $this->update->step = null;
        $this->update->is_finalized = 1;
        $this->update->status = 'ready';

        $templateUpdateId = current(json_decode($this->update->policies, true));

        $policy = $this->available_policies->find($templateUpdateId);

        if ($policy) {
            $policy->refresh(); // refresh policy update to initial state.
            $policy->update(['processed' => true]);
        }

        $this->update->save();

        return $this;
    }

    /**
     * @param $policy_updater_id
     *
     * @return array
     */
    public function getPolicyUpdate($policy_updater_id)
    {
        $updater = PolicyUpdateModel::find($policy_updater_id);

        if (is_null($updater)) {
            return [];
        }

        $data = $updater->toArray();

        $data['start_date'] = date('m/d/Y', strtotime($data['start_date']));

        $policies = json_decode($data['policies'], true);
        $data['policies'] = [];
        foreach ($policies as $policy_updater_id => $policy_template_updates_id) {
            $policy = PolicyTemplate::where('id', $policy_updater_id)->first();

            if ($policy) {
                $data['policies'][$policy_updater_id] = [
                    'policy_updater_id' => $policy->id,
                    'admin_name' => $policy->admin_name,
                    'effective_date' => $policy->effective_date->format('m/d/Y'),
                    'policy_template_updates_id' => $policy_template_updates_id,
                ];
            }
        }

        $emails = $updater->contacts()->primary()->pluck('email');

        $data['emails'] = [];
        // Initial attempts to use Eloquent thusly,
        //  $businesses = Business::with(['users' => function ($query) use ($emails) {... },])->get();
        // was not too fruitful
        $res1 = Business::whereIn('users.email', $emails)
            ->select('users.email as email', 'business.id', 'business.name', 'business.state')
            ->join('users', 'users.business_id', '=', 'business.id')
            ->groupBy('users.email')
            ->get()
            ->keyBy('email')
            ->toArray();

        $res2 = Business::whereIn('business.secondary_1_email', $emails)
            ->select('business.secondary_1_email as email', 'business.id', 'business.name', 'business.state')
            ->groupBy('business.secondary_1_email')
            ->get()
            ->keyBy('email')
            ->toArray();

        $res3 = Business::whereIn('business.secondary_2_email', $emails)
            ->select('business.secondary_2_email as email', 'business.id', 'business.name', 'business.state')
            ->groupBy('business.secondary_2_email')
            ->get()
            ->keyBy('email')
            ->toArray();

        $data['emails'] = array_merge($res1, $res2, $res3);

        ksort($data['emails']);

        $additional_emails = $updater->contacts()->additional()->pluck('email');

        $data['additional_emails'] = [];
        // Initial attempts to use Eloquent thusly,
        //  $businesses = Business::with(['users' => function ($query) use ($additional_emails) {... },])->get();
        // was not too fruitful
        $res1 = Business::whereIn('business.status', ['active', 'renewed'])
            ->select('users.email as email', 'business.id', 'business.name', 'business.state')
            ->join('users', 'users.business_id', '=', 'business.id')
            ->whereIn('users.email', $additional_emails)
            ->whereIn('business.status', ['active', 'renewed'])
            ->groupBy('users.email')
            ->get()
            ->keyBy('email')
            ->toArray();
        /*
         * The logic in laravel/app/Console/Commands/PolicyUpdateEmail.php::handle() seems to only send emails to
         * addresses tied to a user, not either of the business secondary fields
         */

        /*
        $res2 = Business::whereIn('business.secondary_1_email', $additional_emails)
            ->select('business.secondary_1_email as email', 'business.id', 'business.name', 'business.state')
            ->groupBy('business.secondary_1_email')
            ->get()
            ->keyBy('email')
            ->toArray();

        $res3 = Business::whereIn('business.secondary_2_email', $additional_emails)
            ->select('business.secondary_2_email as email', 'business.id', 'business.name', 'business.state')
            ->groupBy('business.secondary_2_email')
            ->get()
            ->keyBy('email')
            ->toArray();

        $results = array_merge($res1, $res2, $res3);
        */

        $results = $res1;

        $empty_business = [
            'id' => '',
            'name' => '',
            'state' => '',
        ];

        foreach($additional_emails as $additional_email) {
            $data['additional_emails'][$additional_email] = isset($results[$additional_email]) ? $results[$additional_email] : $empty_business;
        }

        ksort($data['additional_emails']);

        return $data;
    }
}
