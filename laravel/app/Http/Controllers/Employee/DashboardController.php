<?php

namespace App\Http\Controllers\Employee;

use App\Business;
use App\Http\Controllers\Controller;
use App\TimeOff;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use DB;

/**
 * Class DashboardController.
 *
 * Controller for the "regular employee" dashboard. For the owner/manager version of the
 * dashboard, @see \App\Http\Controllers\User\DashboardController
 */
class DashboardController extends Controller
{
    private $employee;

    public $employeeId;

    public $businessId;

    public $businessObject;

    public $business;

    public $timeOff;

    public $job_description;

    public function __construct(ViewAs $viewAs)
    {
        $this->employee = User::findOrFail($viewAs->getUserId());
        $this->employeeId = $this->employee->id;
        $this->businessId = $this->employee->business_id;
        $this->businessObject = Business::findOrFail($this->businessId);
        $this->business = $this->businessObject->name;
        // Filter out timeoff requests that were denied or that have already passed
        $this->timeOff = TimeOff::where('user_id', [$this->employeeId])
          ->where('start_at', '>', Carbon::now())
          ->get();

        if ($this->employee->jobDescriptions) {
            foreach ($this->employee->jobDescriptions as $thing) {
                $this->job_description = $thing->toArray();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('employee.index')
            ->with('employee', $this->employee)
            ->with('jobDescription', $this->job_description)
            ->with('timeOff', $this->timeOff);
    }
}
