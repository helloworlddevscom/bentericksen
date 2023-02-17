<?php

namespace Bentericksen\Employees;

use App\User as UserModel;
use App\Classification;
use Carbon\Carbon;
use DB;

use Bentericksen\History\History;

class User
{

    private $user;
    private $history;
    private $lookup = [
        'dob'               => ['key' => 'Date of Birth', 'type' => 'status'],
        'classification_id' => ['key'  => 'Classification ID',
                                'type' => 'status',
        ],
        'job_reports_to'    => ['key' => 'Job Reports To', 'type' => 'job'],
    ];

    private $employeeFields = [
        'hired'                   => 'Hired',
        'dob'                     => 'Date of Birth',
        'first_name'              => 'First Name',
        'middle_name'             => 'Middle Name',
        'last_name'               => 'Last Name',
        'prefix'                  => 'Prefix',
        'suffix'                  => 'Suffix',
        'address1'                => 'Address 1',
        'address2'                => 'Address 2',
        'city'                    => 'City',
        'postal_code'             => 'Postal Code',
        'phone1_type'             => 'Phone 1 Type',
        'phone1'                  => 'Phone 1',
        'phone2_type'             => 'Phone 2 Type',
        'phone2'                  => 'Phone 2',
        'email'                   => 'Email',
        'job_reports_to'          => 'Job Reports To',
        'job_location'            => 'Job Location',
        'job_department'          => 'Job Department',
        'can_access_system'       => 'Can Access System',
        'new_classification_id'   => 'New Classification ID',
        'new_classification_name' => 'New Classification Name',
        'classification_date'     => 'Classification Date',
        'employee_status'         => 'Employee Status',
    ];

    public function __construct($userId)
    {
        $this->user = UserModel::find($userId);
        $this->history = new History;
        $this->classifications = Classification::where('business_id', $this->user->business_id);
    }

    public function save($values)
    {
        $businessId = $this->user->business_id;
        $classifications = DB::table('classifications')
            ->where('business_id', $businessId)
            ->get();

        if ((isset($values['new_classification_id']) && $values['new_classification_id'] === "other") && (isset($values['new_classification_name']) && $values['new_classification_name'] !== "")) {
            $values['new_classification_id'] = $this->newClassification($values['new_classification_name']);
            unset($values['new_classification_name']);
        }

        if (isset($values['new_classification_name'])) {
            unset($values['new_classification_name']);
        }

        $now = Carbon::now();
        $effective = new Carbon($values['classification_date']);
        $classification_id = $values['new_classification_id'];
        $classification_updated = 1;

        if ($now->gte($effective) == false) {
            $classification_updated = 0;

            if (isset($values['new_classification_id'])) {
                unset($values['new_classification_id']);
            }

        }

        if (!empty($values['classification_date'])) {
            $classification_info = [
                "user_id"           => $this->user->id,
                "classification_id" => $classification_id,
                "effective_at"      => Carbon::createFromTimestamp(strtotime($values['classification_date']))
                    ->setTime(0, 0, 0),
                "updated"           => $classification_updated,
                'created_at'        => (new Carbon)->format('Y-m-d h:i:s'),
            ];

            DB::table('classification_updates')->insert($classification_info);
        }

        foreach ($values as $key => $value) {
            switch ($key) {
                case 'hired':
                case 'rehired':
                case 'on_leave':
                case 'dob':
                case 'benefit_date':
                    if ($value == "" || $value == "01/01/1970") {
                        $this->user->{$key} = null;
                    } else {
                        $this->user->{$key} = Carbon::createFromFormat('m/d/Y', $value)
                            ->setTime(0, 0, 0);
                    }
                    break;

                case 'job_title_id':
                    $this->user->{$key} = $value == "" ? 0 : $value;
                    break;

                case 'new_classification_id':
                    if ($value != "") {
                        $this->user->classification_id = $value;
                    }
                    break;
                case 'classification_date':
                case 'classification_credit':
                    break;

                default:
                    $this->user->{$key} = $value;
                    break;
            }
        }

        $original = $this->user->getOriginal();
        $newData = $this->user->toArray();

        unset($original['password']);
        unset($original['remember_token']);

        $changes = array_diff_assoc($original, $newData);
        foreach ($changes as $changeKey => $change) {
            $changes[$changeKey] = [
                'before' => $this->user->getOriginal($changeKey),
                'after'  => $this->user->{$changeKey},
            ];
        }

        $this->user->save();

        foreach ($changes as $changeKey2 => $change) {
            $data = [
                'user_id'     => $this->user->id,
                'business_id' => $this->user->business_id,
                'created_at'  => (new Carbon)->format('Y-m-d h:i:s'),
                'status'      => 'active',
                'note'        => '',
            ];

            $data['type'] = 'status';

            foreach ($classifications as $classificationKey => $classification) {
                if ($changeKey2 === 'classification_id' && $change['before'] === 0) {
                    $change['before'] = 'Unclassified';
                } elseif ($changeKey2 === 'classification_id' && $change['before'] == $classification->id) {
                    $change['before'] = $classification->name;
                } elseif ($changeKey2 === 'classification_id' && $change['after'] == $classification->id) {
                    $change['after'] = $classification->name;
                }
            }

            foreach ($this->employeeFields as $fieldKey => $field) {
                if ($fieldKey === $changeKey2 && ($changeKey2 !== 'phone1_type' && $changeKey2 !== 'phone2_type')) {
                    if ($fieldKey == 'classification_date') {
                        $data['note'] .= $values['classification_date']." | ";
                    }
                    $data['note'] .= $field." changed from ".$change['before']." to ".$change['after'];
                }
            }

            if (isset($this->lookup[$changeKey2])) {
                if ($changeKey2 !== 'phone1_type' && $changeKey2 !== 'phone2_type') {
                    $data['type'] = $this->lookup[$changeKey2]['type'];

                    if ($changeKey2 == 'classification_id') {
                        $data['note'] .= $values['classification_date']." | ";
                        $data['type'] = 'Classification';

                    }
                    $data['note'] .= $data['type']." changed from ".$change['before']." to ".$change['after'];
                }
            }

            if ($changeKey2 !== "dob" && $changeKey2 !== 'phone1_type' && $changeKey2 !== 'phone2_type') {
                $this->history->add($data);
            }

        }

        $this->history->save();
    }

    public function getId()
    {
        return $this->user->id;
    }

    public function getBusinessId()
    {
        return $this->user->business_id;
    }

    public function getStatus()
    {
        return $this->user->status;
    }

    public function canRehire()
    {
        return $this->user->can_rehire;
    }

    public function checkHasRole($role)
    {
        return $this->user->hasRole($role);
    }

    public function getClassificationId()
    {
        return $this->user->classification_id;
    }

    public function getClassificationName()
    {
        $classification = DB::table('classifications')
            ->where('id', $this->user->classification_id)
            ->first();
        if (is_null($classification)) {
            return "None Assigned";
        }

        return $classification->name;
    }

    public function getJobTitleId()
    {
        return $this->user->job_title_id;
    }

    public function getHired()
    {
        return $this->user->hired;
    }

    public function getRehired()
    {
        return $this->user->rehired;
    }

    public function getTerminated()
    {
        return $this->user->terminated;
    }

    public function getYearsOfService()
    {
        if ($this->user->hired) {
            $yos = new Carbon($this->user->hired);

            return $yos->age;
        } else {
            return null;
        }
    }

    public function getBenefitDate()
    {
        if (is_null($this->user->benefit_date)) {
            return $this->user->hired;
        }

        return $this->user->benefit_date;
    }

    public function getBenefitYearsOfService()
    {
        $byos = new Carbon($this->getBenefitDate());

        return $byos->age;
    }

    public function getDob()
    {
        return $this->user->dob;
    }

    public function getAge()
    {
        if (!is_null($this->user->dob)) {
            $dob = new Carbon($this->user->dob);

            return $dob->age;
        }

        return null;
    }

    public function getFirstName()
    {
        return $this->user->first_name;
    }

    public function getMiddleName()
    {
        return $this->user->middle_name;
    }

    public function getLastName()
    {
        return $this->user->last_name;
    }

    public function getPrefix()
    {
        return $this->user->prefix;
    }

    public function getSuffix()
    {
        return $this->user->suffix;
    }

    public function getAddress1()
    {
        return $this->user->address1;
    }

    public function getAddress2()
    {
        return $this->user->address2;
    }

    public function getCity()
    {
        return $this->user->city;
    }

    public function getState()
    {
        return $this->user->state;
    }

    public function getPostalCode()
    {
        return $this->user->postal_code;
    }

    public function getPhone1Type()
    {
        return $this->user->phone1_type;
    }

    public function getPhone1()
    {
        return $this->user->phone1;
    }

    public function getPhone2Type()
    {
        return $this->user->phone2_type;
    }

    public function getPhone2()
    {
        return $this->user->phone2;
    }

    public function getEmail()
    {
        return $this->user->email;
    }

    public function getLastLogin()
    {
        return $this->user->last_login;
    }

    public function getOnLeave()
    {
        return $this->user->on_leave;
    }

    public function getCanAccessSystem()
    {
        return $this->user->can_access_system;
    }

    public function getIncludedInEmployeecount()
    {
        return $this->user->included_in_employee_count;
    }

    public function getReceivesBenefits()
    {
        return $this->user->receives_benefits;
    }

    public function getJobDescriptions()
    {
        $ids = DB::table('user_job_description')
            ->where('user_id', $this->user->id)
            ->pluck('job_description_id');

        $jobs = \App\JobDescription::whereIn('id', $ids)->get();

        return $jobs;
    }

    public function getJobReportsTo()
    {
        return $this->user->job_reports_to;
    }

    public function getJobLocation()
    {
        return $this->user->job_location;
    }

    public function getJobDepartment()
    {
        return $this->user->job_department;
    }

    private function newClassification($name)
    {
        $now = Carbon::now();
        $data = [
            'name'                   => $name,
            'business_id'            => $this->user->business_id,
            'is_base'                => 0,
            'is_enabled'             => 1,
            'minimum_hours'          => 1,
            'minimum_hours_interval' => 'day',
            'maximum_hours'          => 8,
            'maximum_hours_interval' => 'day',
            'created_at'             => $now->toDateTimeString(),
            'updated_at'             => '0000-00-00 00:00:00',
        ];

        $result = \App\Classification::create($data);

        return $result->id;
    }

    public function getSalary()
    {
        $salary['salary'] = $this->user->salary;
        $salary['rate'] = $this->user->salary_rate;

        if ($salary['salary'] === null) {
            $salary['salary'] = 0.00;
        }

        if ($salary['rate'] === null) {
            $salary['rate'] = 'hour';
        }

        return $salary;
    }

    /**
     * Returns Employee status field value. Returns "non-exempt" if
     * value is not set.
     * @return mixed
     */
    public function getEmployeeStatus()
    {
        return !$this->user->employee_status || $this->user->employee_status == '' ? 'non-exempt' : $this->user->employee_status;
    }


    /**
     * Returns User's position title.
     * @return mixed
     */
    public function getPositionTitle()
    {
        return $this->user->position_title;
    }
}