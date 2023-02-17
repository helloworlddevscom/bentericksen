<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeesWizardController extends Controller
{
    protected $user;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('user.employees.wizard.index');
    }

    public function staff()
    {
        return view('user.employees.wizard.staff');
    }

    public function staffExcelUpload(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $data = $reader->toArray();
            $businessId = $this->user->business_id;
            $result = new \Bentericksen\Wizard\Employee\CreateEmployees($businessId, $data);
        });

        return redirect('/user/employees/wizard/initial-benefits');
    }

    public function initialBenefits()
    {
        $employees = User::where('business_id', $this->user->business_id)
                            ->where('employee_wizard', 1)
                            ->get();

        return view('user.employees.wizard.initial-benefits', compact(['employees']));
    }

    public function accessibility()
    {
        return view('user.employees.wizard.accessibility');
    }

    public function accessibilityUpdate(Request $request)
    {
        $access = $request->input('employee_access');

        $employees = User::where('business_id', $this->user->business_id)
                            ->where('employee_wizard', 1)
                            ->get();

        foreach ($employees as $employee) {
            $employee->can_access_system = $access;
            $employee->employee_wizard = 0;
            $employee->save();
        }

        return redirect('/user/employees/wizard/complete');
    }

    public function complete()
    {
        return view('user.employees.wizard.complete');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function buttons()
    {
        return '';
    }
}
