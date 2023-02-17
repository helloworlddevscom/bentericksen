<?php

namespace App\Http\Controllers\BonusPro;

use App\BonusPro\Fund;
use App\BonusPro\Month;
use App\BonusPro\MonthFund;
use App\BonusPro\MonthUser;
use App\BonusPro\Plan;
use App\BonusPro\PlanAddress;
use App\Http\Controllers\Controller;
use App\Http\Requests\BonusProNewEmployeeRequest;
use App\Http\Requests\BonusProNewFundRequest;
use App\Http\Requests\BonusProNewPlanRequest;
use App\Http\Requests\BonusProPlanStaffBonusRequest;
use App\User;
use Bentericksen\BonusPro\BonusProReportBuilder;
use Bentericksen\Settings\States;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BonusProController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(ViewAs $viewAs)
    {
        $this->middleware('bonuspro.plans', ['only' => ['show', 'edit', 'resume']]);

        $this->user = $viewAs->getUser();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      return $this->inertia('User/BonusPro/Index', [
        'plans' => $this->user->business->plans,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->inertia('User/BonusPro/Create', [
          'states' => $this->_getStates(),
          'plan' => null,
          'businessUsers' => $this->user->business->users()->with('roles')->get(),
        ]);
    }

    /**
     * Resumes the plan creation step.
     *
     * @param $id
     *
     * @return Response
     */
    public function resume($id)
    {
        $plan = Plan::find($id)->load(['months.employeeData', 'funds', 'users.roles', 'address']);

        if ($plan->use_business_address) {
            $plan->address = [
                'address_1' => $plan->business->address_1,
                'address_2' => $plan->business->address_id,
                'city' => $plan->business->city,
                'state' => $plan->business->state,
                'zip' => $plan->business->zip,
                'phone' => $plan->business->phone,
            ];
        }

        return $this->inertia('User/BonusPro/Create', [
          'states' => $this->_getStates(),
          'plan' => $plan,
          'businessUsers' => $this->user->business->users()->with('roles')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BonusProNewPlanRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(BonusProNewPlanRequest $request)
    {
        $data = $request->except('_token');
        $data['created_by'] = $this->user->id;
        $data['reset_by'] = $this->user->id;
        $data['business_id'] = $this->user->business_id;

        Plan::create($data);

        return redirect('/bonuspro');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return redirect()->action('\App\Http\Controllers\BonusPro\BonusProController@edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $plan = Plan::find($id)->load(['months.funds', 'months.employeeData', 'funds', 'users.roles', 'address']);

        if (! $plan->completed) {
            return redirect()->route('bonuspro.resume', ['id' => $plan->id]);
        }

        return $this->inertia('User/BonusPro/Edit', [
          'states' => $this->_getStates(),
          'plan' => $plan,
          'businessUsers' => $this->user->business->users()->with('roles')->get(),
          'years' => $this->_getYears(2000),
          'months' => $this->_getMonths(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::load($id);
        $data = $request->except(['_token']);

        return redirect();
    }

    /**
     * Confirms Password and Delete plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmAndDelete(Request $request)
    {
        $password = $request->input('password');
        $id = $request->input('plan_id');

        $plan = Plan::find($id);

        if (Hash::check($password, $plan->password)) {
            $plan->delete();

            return response()->json(['valid' => true, 'message' => 'Plan deleted successfully.']);
        } else {
            return response()->json(['valid' => false, 'message' => 'Invalid Password. Try again.']);
        }
    }

    /**
     * Confirms Plan's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPlanPassword(Request $request)
    {
        $password = $request->input('password');
        $id = $request->input('plan_id');

        $plan = Plan::find($id);

        if (Hash::check($password, $plan->password)) {
            $valid = true;
        } else {
            $valid = false;
        }

        return response()->json(['valid' => $valid]);
    }

    /**
     * Store (or update) Plan Data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function savePlanData(Request $request)
    {
        $plan = Plan::find($request->input('id'))->load('months');

        foreach ($request->input('months') as $month) {
            $month['production_collection_average'] = $month['productionCollectionAverage'];

            unset($month['collectionRatio']);
            unset($month['productionCollectionAverage']);

            if (empty($month['id'])) {
                $plan->months()->save(new Month($month));
            } else {
                Month::find($month['id'])->update($month);
            }
        }

        $plan->refresh();

        return $request->expectsJson() ? response()->json([
            'message' => 'Plan created successfully.',
            'plan' => $plan,
        ]) : redirect('/bonuspro');
    }

    /**
     * Store (or update) Plan Initial Setup Draft.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveInitialSetupDraft(Request $request)
    {
        $id = $request->input('id');

        $data = $request->all();

        $data['draft'] = 1;

        if ($id) {
            $plan = Plan::find($id);
            $plan->update($data);
        } else {
            $plan = Plan::create($data);
        }

        if (! $request->input('use_business_address')) {
            $addressData = $request->input('address');
            if (! $plan->address) {
                $plan->address()->save(new PlanAddress($addressData));
            } else {
                $plan->address()->update($addressData);
            }
        }

        return $request->expectsJson() ? response()->json([
            'message' => 'Initial draft set successfully.',
            'plan' => $plan,
        ]) : redirect('/bonuspro');
    }

    /**
     * Store (or update) Plan Initial Setup.
     *
     * @param BonusProNewPlanRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveInitialSetup(BonusProNewPlanRequest $request)
    {
        $id = $request->input('id');

        $data = $request->all();
        $data['draft'] = 0;

        if ($id) {
            $plan = Plan::find($id);
            $plan->update($data);
        } else {
            $plan = Plan::create($data);
        }

        if (! $request->input('use_business_address')) {
            $addressData = $request->input('address');
            if (! $plan->address) {
                $plan->address()->save(new PlanAddress($addressData));
            } else {
                $plan->address()->update($addressData);
            }
        }

        return $request->expectsJson() ? response()->json([
            'message' => 'Initial data set successfully.',
            'plan' => $plan,
        ]) : redirect('/bonuspro');
    }

    /**
     * Removing Fund.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeFund(Request $request)
    {
        $id = $request->input('id');
        Fund::find($id)->delete();

        return $request->expectsJson() ? response()->json([
            'message' => 'Fund removed successfully.',
            'fund_id' => $id,
        ]) : redirect('/bonuspro');
    }

    /**
     * Adding employee to plan.
     *
     * @param BonusProNewEmployeeRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addEmployee(BonusProNewEmployeeRequest $request)
    {
        $data = $request->except(['roles', 'plan_id', 'role_id', 'monthlyData', 'pivot', 'created_at', 'updated_at']);
        $plan = Plan::find($request->input('plan_id'));
        $user = User::find($request->input('id'));

        if (isset($data['distribution_type'])) {
            unset($data['distribution_type']);
        }

        // if changes were made in the request, update the model.
        // Fields being updated (potentially): email, first_name, last_name, role, eligibility date, bonus_percentage
        $user->update($data);

        $plan->users()->syncWithoutDetaching($user->id);

        $this->_setUsersMonthlyData($request->input('monthlyData'), $plan, $user);

        return $request->expectsJson() ? response()->json([
            'message' => 'Employee added successfully.',
        ]) : redirect('/bonuspro');
    }

    /**
     * Removing employee from plan.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeEmployee(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::find($user_id)->load('salaryData');
        $plan = Plan::find($request->input('plan_id'))->load(['months.employeeData']);

        $plan->users()->detach($user_id);

        $user->salaryData()->whereIn('month_id', $plan->months()->pluck('id'))->delete();

        return $request->expectsJson() ? response()->json([
            'message' => 'Employee removed successfully.',
            'user_id' => $request->input('id'),
        ]) : redirect('/bonuspro');
    }

    /**
     * Creates an employee and add it to the business.
     *
     * @param BonusProNewEmployeeRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createEmployee(BonusProNewEmployeeRequest $request)
    {
        $data = $request->except(['roles', 'monthlyData', 'plan_id']);
        $plan = Plan::find($request->input('plan_id'));

        $data['password'] = bcrypt(Str::random(12));

        if (isset($data['distribution_type'])) {
            unset($data['distribution_type']);
        }

        $employee = User::create($data);
        $employee->roles()->attach(5); // always an employee

        $plan->users()->syncWithoutDetaching($employee->id);
        $this->_setUsersMonthlyData($request->input('monthlyData'), $plan, $employee);

        $employee->load('salaryData');

        $employee->monthlyData = $employee->salaryData;

        return $request->expectsJson() ? response()->json([
            'message' => 'Employee created and added to the plan successfully.',
            'employeeData' => $employee,
        ]) : redirect('/bonuspro');
    }

    /**
     * Adding fund to plan.
     *
     * @param BonusProNewFundRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveFund(BonusProNewFundRequest $request)
    {
        $plan = Plan::find($request->input('plan_id'));
        $fund = $plan->funds()->save(new Fund($request->all()));

        return $request->expectsJson() ? response()->json([
            'message' => 'Fund created successfully.',
            'fund' => $fund,
        ]) : redirect('/bonuspro');
    }

    /**
     * Update fund.
     *
     * @param BonusProNewFundRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateFund(BonusProNewFundRequest $request)
    {
        $fund = Fund::find($request->input('id'));
        $fund->update($request->all());

        return $request->expectsJson() ? response()->json([
            'message' => 'Fund updated successfully.',
            'fund' => $fund,
        ]) : redirect('/bonuspro');
    }

    /**
     * Saving monthly data for open month.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveMonthData(Request $request)
    {
        $monthlyData = $request->input('monthlyData');
        $activeMonthData = $request->input('activeMonthData');
        $fundsData = $request->input('fundsData');
        $month = Month::find($activeMonthData['monthId']);

        unset($activeMonthData['monthId']);

        $activeMonthData['finalized'] = true;

        // loop through data passed from Vue app and decide whether to create a MonthUser row or update.
        foreach ($monthlyData as $i => $data) {
            if (empty($data['id'])) {
                $monthUserRow = new MonthUser($data);
                $monthUserRow->save();

                // updating ID for the month data row to be updated in the Vue App.
                $monthlyData[$i]['id'] = $monthUserRow->id;
            } else {
                MonthUser::find($data['id'])->update($data);
            }
        }

        foreach ($fundsData as $i => $data) {
            if (empty($data['id'])) {
                $monthFundRow = new MonthFund($data);
                $monthFundRow->save();

                // updating ID for the month data row to be updated in the Vue App.
                $fundsData[$i]['id'] = $monthFundRow->id;
            } else {
                MonthFund::find($data['id'])->update($data);
            }
        }

        $month->update($activeMonthData);

        $plan = Plan::find($month['plan_id']);

        $newMonth = $this->_addNewMonth($plan);

        // add the new month to the monthly data array to be returned.
        $monthlyData = array_merge($monthlyData, $newMonth->employeeData->toArray());
        $fundsData = array_merge($fundsData, $newMonth->funds->toArray());

        return $request->expectsJson() ? response()->json([
            'message' => 'Monthly data saved successfully.',
            'monthlyData' => $monthlyData,
            'newMonth' => $newMonth,
            'fundsData' => $fundsData,
        ]) : redirect('/bonuspro');
    }

    /**
     * Store (or update) Plan Bonus Percentages.
     *
     * @param BonusProPlanStaffBonusRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveBonusPercentage(BonusProPlanStaffBonusRequest $request)
    {
        $id = $request->input('id');

        $plan = Plan::findOrFail($id);
        $data = $request->all();

        // the plan needs to be flagged as completed in the last step.
        $data['completed'] = true;

        $plan->update($data);

        // closing each month in the plan
        foreach ($plan->months as $month) {
            $month->closeMonth();
        }

        // adding the next month for the plan.
        $this->_addNewMonth($plan);
        $request->session()->flash('success', 'BonusPro Plan created successfully.');

        return $request->expectsJson() ? response()->json([
            'plan' => $plan,
        ]) : redirect('/bonuspro');
    }

    /**
     * Store (or update) Plan Bonus Percentages Draft.
     *
     * @param BonusProPlanStaffBonusRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveBonusPercentageDraft(Request $request)
    {
        $id = $request->input('id');

        $plan = Plan::findOrFail($id);
        $data = $request->all();

        // the plan needs to be flagged as completed in the last step.
        $data['draft'] = true;

        $plan->update($data);

        return $request->expectsJson() ? response()->json([
            'message' => 'Draft saved successfully',
            'plan' => $plan,
        ]) : redirect('/bonuspro');
    }

    /**
     * Returns array with states and abbr. for form.
     *
     * @return array
     */
    private function _getStates()
    {
        $statesModel = new States();
        $states = $statesModel->businessStates();

        unset($states['Non-MT']);
        unset($states['ALL']);

        return $states;
    }

    /**
     * Display a list of the employees associated with the plan.
     *
     * @param  Plan $plan
     *   A BonusPro Plan object that the employees will be associated with
     * @return Response
     */
    public function employees(Plan $plan)
    {
        return view('bonuspro.plan_employees')->with([
            'plan' => $plan,
            'employees' => $plan->users,
        ]);
    }

    /**
     * Generates a report.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function generateReport(Request $request)
    {
        $settings = $request->all();
        $plan = Plan::find($settings['planId']);
        $report = new BonusProReportBuilder($plan, $settings);

        return $request->expectsJson() ? response()->json([
            'report' => base64_encode($report->buildReport()),
        ]) : redirect('/bonuspro');
    }

    /**
     * Sets users monthly data.
     *
     * @param $monthlyData
     * @param $plan
     * @param $user
     */
    private function _setUsersMonthlyData($monthlyData, $plan, $user)
    {
        foreach ($monthlyData as $monthly) {
            $planMonth = $plan->months()->where('month', $monthly['month'])->first();
            $monthly['user_id'] = $user->id;
            $monthly['plan_id'] = $plan->id;
            if (empty($monthly['id'])) {
                // creating
                $planMonth->employeeData()->save(new MonthUser($monthly));
            } else {
                // updating
                $monthUser = MonthUser::find($monthly['id']);
                $monthUser->update($monthly);
            }
        }
    }

    /**
     * Adding a new month to the plan.
     *
     * @param $plan Plan
     * @return mixed
     */
    private function _addNewMonth($plan)
    {
        $previousMonth = $plan->months->last();
        $date = Carbon::create($previousMonth->year, $previousMonth->month, 1, 1, 0, 0, 'America/Los_Angeles');
        $date->addMonth();

        $newMonth = [
            'plan_id' => $previousMonth->plan_id,
            'month' => $date->month,
            'year' => $date->year,
        ];

        $month = Month::create($newMonth);

        foreach ($plan->users as $user) {
            $month->employeeData()->create(['month_id' => $month->id, 'user_id' => $user->id]);
        }

        foreach ($plan->funds as $fund) {
            $month->funds()->create(['month_id' => $month->id, 'fund_id' => $fund->id]);
        }

        return $month;
    }

    /**
     * Helper function that returns an array of Years for BP forms.
     *
     * @param $startYear integer
     *
     * @return array
     */
    private function _getYears($startYear)
    {
        return range(date('Y'), $startYear);
    }

    /**
     * Helper function to get the month array for BP forms.
     *
     * @return mixed|array
     */
    private function _getMonths()
    {
        return array_reduce(range(1, 12), function ($array, $month) {
            $array[$month] = date('M', mktime(0, 0, 0, $month, 10));

            return $array;
        });
    }
}
