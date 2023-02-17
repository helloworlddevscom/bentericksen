<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeOffRequestsRequest;
use App\TimeOff;
use App\User;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class TimeOffController.
 *
 * The owner/manager view of the time off requests pages. For the "regular employee" version,
 * @see \App\Http\Controllers\Employee\TimeOffRequestController
 */
class TimeOffController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $types;

    /**
     * TimeOffController constructor.
     *
     * @param \Bentericksen\ViewAs\ViewAs $viewAs
     */
    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());
        $this->types = [
            'pregnancy' => 'Pregnancy',
            'disability' => 'Disability',
            'workers_compensation' => 'Worker\'s Compensation',
            'medical' => 'Medical',
            'military' => 'Military',
            'domestic_violence' => 'Domestic Violence',
            'other' => 'Other',
            'time_off' => 'Time Off',
            'medical_leave' => 'Medical Leave',
            'pregnancy_leave' => 'Pregnancy Leave',
            'personal_leave_of_absence' => 'Personal Leave of Absence',
            'paid_time_off' => 'Paid Time Off',
            'unpaid_time_off' => 'Unpaid Time Off'
        ];
    }

    /**
     * Renders the "time off requests" page, containing a form and calendar.
     * (The calendar view itself is loaded by AJAX. This route only renders
     * the container for it.)
     * URL: /user/employees/time-off-requests.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($viewId = null)
    {
        $types = $this->types;

        $employees = User::where('business_id', $this->user->business_id)
            ->where('status', 'enabled')
            ->get();

        $employeeNames = User::whereIn('id', $employees->pluck('id'))
            ->where('status', 'enabled')
            ->select('id', 'first_name', 'last_name')
            ->get();

        $timeOffRequests = TimeOff::whereIn('user_id', $employees->pluck('id'))
            ->get();

        // get the user then
        foreach ($timeOffRequests as $key => $value) {
            $timeOffRequests[$key]->employee = '';
            foreach ($employeeNames as $name) {
                if ($timeOffRequests[$key]->user_id === $name->id) {
                    $timeOffRequests[$key]->employee = $name->first_name.' '.$name->last_name;
                }
            }

            foreach ($types as $typeKey => $typeValue) {
                if ($timeOffRequests[$key]->type === $typeKey) {
                    $timeOffRequests[$key]->type = $typeValue;
                }
            }

            // If start date is 0, skip time off request.
            // @todo: Need to validate start date is valid during input.
            if ($value->getRawOriginal()['start_at'] == '0000-00-00 00:00:00') {
                unset($timeOffRequests[$key]);
                continue;
            }

            // Account for situations where the `end_at` date is 0.
            // @todo: Should validate during user input that `end_at` is not 0,
            // should always at least be the same as `start_date`.
            if ($value->getRawOriginal()['end_at'] == '0000-00-00 00:00:00') {
                $value->end_at = $value->start_at;
            }

            $timeOffRequests[$key]->created = (Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at, 'PST')->timestamp) * 1000;
            $timeOffRequests[$key]->start = (Carbon::createFromFormat('Y-m-d H:i:s', $value->start_at, 'PST')->timestamp) * 1000;
            $timeOffRequests[$key]->end = (Carbon::createFromFormat('Y-m-d H:i:s', $value->end_at, 'PST')->timestamp) * 1000;
        }

        $startingDate = ! empty($viewId) ?
            $timeOffRequests->where('id', $viewId)->first()->start_at->format('Y-m-d') :
            date('Y-m-d');

        return view('user.employees.time_off_request', compact([
            'employees',
            'timeOffRequests',
            'types',
            'startingDate',
        ]));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        $timeOffRequest = TimeOff::find($id);

        return view('user.employees.time_off_request_edit')->with([
            'types' => $this->types,
            'employee' => $timeOffRequest->user,
            'timeOffRequest' => $timeOffRequest,
        ]);
    }

    /**
     * Saves a Time Off Request to the database.
     *
     * @param TimeOffRequestsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TimeOffRequestsRequest $request)
    {
        $data = $request->except([
            '_return',
            '_token',
        ]);

        $data['type'] = $this->types[$data['type']];

        // managers who don't have permission to approve can still submit "pending" time off requests
        // for any employee
        if (! $this->user->permissions('m144')) {
            $data['status'] = 'pending';
        }

        TimeOff::create($data);

        return redirect('/user/employees/time-off-requests');
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function timeOffStatusUpdate(Request $request, $id)
    {
        $timeoff = TimeOff::find($id);
        $this->checkTimeOffPermissions($timeoff);

        if ($request->input('action') == 'approve' || $request->input('action') == 'deny') {
            $timeoff->status = $request->input('action') == 'approve' ? 'approved' : 'denied';
            $timeoff->save();
        }

        return redirect($request->input('return', '/user'));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function timeOffDeleteStatus(Request $request, $id)
    {
        $timeoff = TimeOff::find($id);
        $this->checkTimeOffPermissions($timeoff);

        if ($request->input('action') == 'delete') {
            $timeoff->delete();
        }

        return redirect($request->input('return', '/user'));
    }

    /**
     * Helper method - checks whether the current logged in user can see or edit
     * the time off record. Currently, this only prevents users from editing
     * time off requests from other businesses. Throws a 403 if not authorized.
     *
     * @param \App\TimeOff $timeoff The TimeOff object
     */
    private function checkTimeOffPermissions($timeoff)
    {
        // check that the loaded employee is in the current business
        // (for admins/consultants using the "view as" feature, this check will
        // only pass if the employee belongs to the *currently viewed* business.
        if ($timeoff->user->business_id !== $this->user->business_id) {
            abort(403, 'You do not have permission to see this content.');
        }
    }

    /**
     * This method is used to modify an existing time-off request
     * This method is accessed the time-off request calendar single day screen.
     *
     * From user/employees/time-off-requests calendar select a date number of the calendar cell.
     * Time-off requests are shown at the top of the calendar.
     *
     * @param TimeOffRequestsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editRequest(TimeOffRequestsRequest $request)
    {
        if ($request->has('id')) {
            $timeOffRequest = $request->only(['id', 'start_at', 'end_at', 'reason', 'status', 'user_id', 'type']);

            // Grab existing TimeOff Model entry matching id
            $timeOff = TimeOff::find($timeOffRequest['id']);

            // Check permission to access
            $this->checkTimeOffPermissions($timeOff);
            $timeOff->fill($timeOffRequest);
            $timeOff->save();
            $message = 'Time Off Request Updated';
        } else {
            $message = 'Unable to update Employee time-off';
        }

        \Session::flash('message', $message);

        return redirect('/user/employees/time-off-requests/'.$timeOffRequest['id']);
    }
}
