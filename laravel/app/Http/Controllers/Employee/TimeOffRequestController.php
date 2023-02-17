<?php

namespace App\Http\Controllers\Employee;

use App\Business;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeOffRequestsRequest;
use App\TimeOff;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use URL;

/**
 * Class TimeOffRequestController.
 *
 * The "regular employee" version of the Time Off Requests pages.
 * For the owner/admin version, @see \App\Http\Controllers\User\TimeOffController
 */
class TimeOffRequestController extends Controller
{
    //Dashboard Stuff
    private $employee;

    private $employeeId;

    private $businessId;

    private $businessObject;

    private $business;

    private $timeOff;

    public function __construct(ViewAs $viewAs)
    {
        // Dashboard Stuff
        $this->employee = User::findOrFail($viewAs->getUserId());
        $this->employeeId = $this->employee->id;
        $this->businessId = $this->employee->business_id;
        $this->businessObject = Business::findOrFail($this->businessId);
        $this->business = $this->businessObject->name;
        $this->timeOff = TimeOff::where('user_id', [$this->employeeId])->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $employee = new \stdClass();

        $employee->id = $this->employee->id;
        $employee->first_name = $this->employee->first_name;
        $employee->middle_name = $this->employee->middle_name;
        $employee->last_name = $this->employee->last_name;
        $employee->address1 = $this->employee->address1;
        if (isset($this->employee->address2)) {
            $employee->address2 = $this->employee->address2;
        } else {
            $employee->address2 = '';
        }
        $employee->city = $this->employee->city;
        $employee->state = $this->employee->state;
        $employee->postal_code = $this->employee->postal_code;
        $employee->business_name = $this->business;
        $employee->phone1 = $this->employee->phone1;
        $employee->phone1_type = $this->employee->phone1_type;
        $employee->phone2 = $this->employee->phone2;
        $employee->phone2_type = $this->employee->phone2_type;
        $employee->email = $this->employee->email;
        $employee->hired = $this->employee->hired;

        $created = new Carbon($employee->hired);
        $now = Carbon::now();
        $difference = $created->diff($now);
        $employee->years_of_service = $difference->y;

        $employee->rehired = $this->employee->rehired;
        $employee->on_leave = $this->employee->on_leave;
        $employee->benefit_date = $this->employee->benefit_date;
        $employee->benefit_years_of_service = $this->employee->benefit_years_of_service;
        $employee->salary = $this->employee->salary;
        $employee->salary_rate = $this->employee->salary_rate;
        $employee->classification = 'Classification'; //$this->employee->classification;

        // Job Titles not implemented in to the database yet.
        //$jobTitleId = $this->employee->job_title_id;
        $employee->job_title = 'Job Title'; //DB::Statment('SELECT name FROM job_titles WHERE id = ' . $jobTitleId);

        $employee->dob = $this->employee->dob;

        $dobVar = new Carbon($employee->dob);
        $dobM = $dobVar->month;
        $dobD = $dobVar->day;
        $dobY = $dobVar->year;

        $employee->dobM = $dobM;
        $employee->dobD = $dobD;
        $employee->dobY = $dobY;

        $ageCreated = new Carbon($employee->dob);
        $ageNow = Carbon::now();
        $ageDifference = $ageCreated->diff($ageNow);
        $employee->age = $ageDifference->y;

        return view('employee.time-off-request', compact([
            'employee',
        ]));
    }

    public function submitRequest(TimeOffRequestsRequest $request)
    {
        $submit = $request->all();

        $timeOffRequest = new TimeOff;

        $timeOffRequest->user_id = $this->employeeId;

        foreach ($submit as $key => $value) {
            // Excluding total_hours and unpaid_time until I can figure out if i need them or not
            if ($key !== '_token' && $key !== 'total_hours' && $key !== 'unpaid_time') {
                $timeOffRequest->$key = $value;
            }
        }

        $timeOffRequest->save();

        \Session::flash('message', 'Your request was submitted!');

        return redirect('/employee/time-off-request/'.$this->employeeId);
    }
}
