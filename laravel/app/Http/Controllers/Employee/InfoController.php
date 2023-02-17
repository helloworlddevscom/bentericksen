<?php

namespace App\Http\Controllers\Employee;

use App\EmergencyContact;
use App\EmergencyContact as EmergencyContactModel;
use App\Http\Controllers\Controller;
use App\TimeOff;
use App\User;
use Bentericksen\Settings\States;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * @var User
     */
    private $employee;

    /**
     * @var int
     */
    private $employeeId;

    private $timeOff;

    public function __construct(ViewAs $viewAs)
    {
        // Dashboard Stuff
        $employee = User::find($viewAs->getUserId());
        $this->employeeId = $employee->id;
        $this->timeOff = TimeOff::where('user_id', [$this->employeeId])->get();

        if ($employee->emergencyContacts()->count() == 0) {
            $primary_contact = new EmergencyContactModel(['is_primary' => 1]);
            $secondary_contact = new EmergencyContactModel();
            $employee->emergencyContacts()->saveMany([$primary_contact, $secondary_contact]);
            $employee->save();
        }

        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('employee.info')
            ->with('states', $this->getStates())
            ->with('employee', $this->employee);
    }

    /**
     * Update Employee's data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $tempEmployee = $this->employee->toArray();
        $employeeAttributes = array_keys($tempEmployee);

        $keyToRemove = array_search('emergency_contacts', $employeeAttributes);
        if ($keyToRemove) {
            unset($employeeAttributes[$keyToRemove]);
        }

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $employeeAttributes)) {
                $this->employee->$key = $value;
            }
        }

        $this->employee->save();

        $this->updateEmergencyContacts($request, true);

        return redirect('/employee/info/'.$this->employeeId);
    }

    /**
     * Update Emergency Contact records.
     *
     * @param \Illuminate\Http\Request $request
     * @param bool $skipRedirect
     *
     * @return \App\Http\Controllers\Employee\InfoController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateEmergencyContacts(Request $request, $skipRedirect = false)
    {
        $data = $request->input('emergency_contacts');

        foreach ($data as $values) {
            if ($values['relationship'] == 'other') {
                $values['relationship'] = $values['relationship_other'];
            }
            unset($values['relationship_other']);
            $contact = EmergencyContact::find($values['id']);

            foreach ($values as $attr => $new_value) {
                $contact->$attr = $new_value != '' ? $new_value : null;
            }

            if ($contact->isDirty()) {
                $contact->save();
            }
        }

        return $skipRedirect ? $this : redirect('/employee/info/'.$this->employeeId);
    }

    /**
     * Generate list with US states for edit form.
     *
     * @return array
     */
    private function getStates()
    {
        $statesObject = new States();
        $states = $statesObject->businessStates();

        unset($states['ALL']);
        unset($states['Non-MT']);

        return array_merge(['' => '- Select One -'], $states);
    }
}
