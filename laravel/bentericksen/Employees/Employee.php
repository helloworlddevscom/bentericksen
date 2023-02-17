<?php

namespace Bentericksen\Employees;

use Bentericksen\Employees\User as Users;
use Carbon\Carbon;
use DB;

use Bentericksen\ActivateAccount\ActivateAccount;
use Bentericksen\Employees\Benefits\Benefits as Bene;

class Employee
{
    protected $userId;
    public $user;
    public $salary;
    public $benefit;
    public $nonBenefit;
    public $authorization;
    public $license;
    public $licensure;
    public $emergencyContact;
    public $jobDescription;
    public $licensures;
    public $history;

    private $lookup = [
        'emergency' => 'emergencyContacts',
        'salary' => 'salary',
        'user' => 'user',
        'licensure' => 'licensure',
        'license' => 'license',
        'attendance' => 'attendance',
        'paperwork' => 'paperwork',
        'permission' => 'permission',
        'jobs' => 'jobDescription',
        'history'    => 'history',
    ];

    /**
     * Available options for Employee Status field.
     * @var array
     */
    private $employeeStatusOptions = [
        'non-exempt' => 'Non-Exempt',
        'exempt' => 'Exempt',
    ];

    public $bene;

    public $emergencyContacts;

    public $attendance;

    public $paperwork;

    public $permission;

    public $activateAccount;

    public function __construct()
    {
        return $this;
    }

    /**
     * Loads an employee by ID.
     *
     * @param $userId
     *
     * @return $this
     */
    public function load($userId)
    {
        $this->userId = $userId;
        //new class for benefits
        //need to rename this to Benefits and change the calls in the templates to ->benefits->
        $this->bene = new Bene($userId);
        $this->nonBenefit = new NonBenefit($userId);
        $this->user = new Users($userId);
        $this->salary = new Salary($userId);
        $this->emergencyContacts = new EmergencyContact($userId);
        $this->license = new DriversLicense($userId);
        $this->licensure = new LicensureCertification($userId);
        $this->attendance = new Attendance($userId);
        $this->paperwork = new Paperwork($userId);
        $this->permission = new Permission($userId);
        $this->jobDescription = new JobDescription($userId);
        $this->history = new History($userId);

        return $this;
    }

    public function create($request)
    {
        //Create emergency contact information primary and secondary.
        foreach ($request->input('emergency') as $key => $row) {
            $contact = new EmergencyContact($this->userId);
            $contact->create($row, $key);
        }

        $this->salary->save($request);
        $this->license->save($request->license);
        $this->licensure->save($request->licensure);
    }

    public function update($request)
    {
        //This file stuff might should happen before the employee portion?
        if (!is_null($request->file['file'])) {
            $file = $request->file['file'];
            if (!is_null($request->input('_filetype')) && ($request->input('_filetype') == "regular" || $request->input('_filetype') == "confidential")) {
                $data = [
                    'user_id' => $this->user->getId(),
                    'business_id' => $this->user->getBusinessId(),
                    'original_file_name' => $file->getClientOriginalName(),
                    'file_name' => str_random(32),
                    'size' => $file->getClientSize(),
                    'type' => $request->input('_filetype'),
                    'location' => storage_path() . DIRECTORY_SEPARATOR . "bentericksen/uploads",
                    'status' => 'active',
                    'created_at' => (new Carbon)->format('Y-m-d h:i:s'),
                ];

                $file->move($data['location'], $data['file_name']);

                DB::table('file_uploads')->insert($data);
            }
        }

        if ($request->input('resend_email') === 'yes') {
            $this->activateAccount = new ActivateAccount($this->userId);
            return redirect('/user/employees/' . $this->userId . '/edit');
        }

        foreach ($request->request as $key => $value) {
            if (isset($this->lookup[$key])) {
                $class = $this->lookup[$key];
                $this->$class->save($value);
            }
        }
    }

    /**
     * Return available options for Employee Status drop down.
     *
     * @return array
     */
    public function getEmployeeStatusOptions()
    {
        return $this->employeeStatusOptions;
    }
}
