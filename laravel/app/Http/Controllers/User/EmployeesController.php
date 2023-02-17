<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Classification;
use App\ClassificationUpdates;
use App\DriversLicense;
use App\EmergencyContact;
use App\Events\ManagerOwnerCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Requests\NewEmployeeRequest;
use App\Imports\EmployeesImport;
use App\Mail\AccountActivationEmail;
use App\OutgoingEmail;
use App\Paperwork;
use App\Role;
use App\SalaryUpdates;
use App\TimeOff;
use App\User;
use App\Attendance;
use Bentericksen\Employees\CreateEmployee;
use Bentericksen\Settings\States;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\PerformStreamdentUserProcess;
use App\Jobs\UpdatePolicies;

class EmployeesController extends Controller
{
    /**
     * @var int
     */
    private $businessId;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Role
     */
    private $roles;

    /**
     * @var Business
     */
    private $business;

    /**
     * @var States
     */
    protected $states;

    /**
     * @var array
     */
    protected $types;

    /**
     * @var array
     */
    protected $terminationKeys = [
        'resignation' => 'Resignation',
        'discharge' => 'Discharge',
        'layoff' => 'Layoff',
        'job_abandonment' => 'Job Abandonment',
    ];

    /**
     * @var CreateEmployee
     */
    private $userFactory;

    /**
     * @var array
     */
    private $phoneTypes;

    /**
     * @var array
     */
    private $employeeStatus;

    public function __construct(ViewAs $viewAs)
    {
        // Note: The controller constructor runs before the middleware, so if the user isn't
        // logged in, you get a 404 instead of a redirect to the login form.
        // @todo Refactor this to not use findOrFail() in the constructor
        $this->user = User::findOrFail($viewAs->getUserId());
        $this->businessId = $this->user->business_id;
        $this->roles = Role::whereIn('name', ['owner', 'manager', 'employee'])->get();
        $this->states = new States;
        $this->userFactory = new CreateEmployee;

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

        $this->employeeStatus = [
            'non-exempt' => 'Non-Exempt',
            'exempt' => 'Exempt',
        ];

        $this->phoneTypes = [
            'home' => 'Home',
            'cell' => 'Cell',
            'work' => 'Work',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->user->business->users()
            ->where('included_in_employee_count', 1)
            ->orderBy('status', 'desc')
            ->get();

        return view('user.employees.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->roles;
        if ($this->user->hasRole('manager')) {
            unset($roles[0]);
        }

        return view('user.employees.create')
            ->with([
                'states' => $this->states->states(),
                'employeeStatus' => $this->employeeStatus,
                'roles' => $this->roles,
                'phoneTypes' => $this->phoneTypes,
                'classifications' => $this->user->business->classifications,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewEmployeeRequest $request
     *
     * @return Response
     */
    public function store(NewEmployeeRequest $request)
    {
        $data = $request->except(['_method', '_token']);
        $contact = $data['contact'];
        $contact['password'] = bcrypt(Str::random(12));
        $role = $contact['user_role'];

        unset($contact['user_role']);

        $employee = $this->user->business->users()->create($contact);

        $employee->roles()->attach($role);

        $managerRoles = collect(['admin', 'owner', 'manager', 'consultant'])
            ->diff($employee->getRoleNames());

        if ($managerRoles->count() < 4) {
          event(new ManagerOwnerCreated($employee));
        }

        $this->updateDriversLicense($employee, $data['drivers_license']);
        $this->updateEmergencyContacts($employee, $data['emergency']);
        $this->updateEmployeeClassification($employee, $data['employee_classification']);
        $this->updateEmployeeSalary($employee, $data['salary']);
        if (isset($data['licensure'])) {
            $this->updateLicensures($employee, $data['licensure']);
        }

        if ($data['employee']['activation_email'] === '1') {
            $this->resendAuthorizationEmail($employee);
        }

        return redirect('user/employees')
            ->withSuccess('Employee created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $viewPath = 'user.employees.tabs';
        $tabs = [
            (object) [
                'name' => 'tracking',
                'view' => $viewPath.'.tracking',
            ],
            (object) ['name' => 'history', 'view' => $viewPath.'.history'],
            (object) [
                'name' => 'authorization',
                'view' => $viewPath.'.authorization',
            ],
        ];

        $employee = User::find($id)->load([
            'salary',
            'classification',
            'business',
            'business.classifications',
            'attendance',
            'timeOff',
            'jobDescriptions',
            'paperwork',
        ]);

        $licensures = $employee->business->getLicensureOptions();

        return $this->inertia('User/Employees/Edit', [
                'storeDispatch' => [
                  'employees/setData',
                  [
                    'employee' => $employee,
                    'employeeStatus' => $this->employeeStatus,
                    'states' => $this->states->states(),
                    'classifications' => $employee->business->classifications,
                    'phoneTypes' => $this->phoneTypes,
                    'tabs' => $tabs,
                    'leaveTypes' => $this->types,
                    'paperwork' => Paperwork::all(),
                    'roles' => $this->roles,
                    'licensures' => $licensures,
                  ]]
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\EmployeeUpdateRequest $request
     * @param  int $id
     *
     * @return
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        $employee = User::find($id);
        $this->checkEmployeePermissions($employee);
        $data = $request->except(['_method', '_token']);
        $action = $request->input('action');

        switch ($action) {
            case 'personal':
            case 'contact':
            case 'employment_status':
            case 'job_description':
                $this->updateInfo($employee, $data[$action]);
                break;
            case 'employment_termination':
                $this->updateEmployeeTerminate($employee, $data[$action]);
                break;
            case 'salary':
                $this->updateEmployeeSalary($employee, $data[$action]);
                break;
            case 'employee_classification':
                $this->updateEmployeeClassification($employee, $data[$action]);
                break;
            case 'emergency':
                $this->updateEmergencyContacts($employee, $data[$action]);
                break;
            case 'drivers_license':
                $this->updateDriversLicense($employee, $data[$action]);
                break;
            case 'licensure':
                if (isset($data[$action])) {
                    $this->updateLicensures($employee, $data[$action]);
                }
                break;
            case 'attendance':
                $this->updateAttendance($employee, $data[$action]);
                break;
            case 'leave_of_absence':
            case 'leave_of_absence_update':
                $this->updateLeaveOfAbsence($employee, $data[$action]);
                break;
            case 'paperwork':
                if(!isset($data[$action])) {
                    $data[$action] = ["paperwork_id" => []];
                }
                $this->updatePaperwork($employee, $data[$action]);
                break;
            case 'authorization':
                $this->updateAuthorization($employee, $data[$action]);
                break;
            case 'resend_email':
                $this->resendAuthorizationEmail($employee);
                break;
        }

        $_url = '/user/employees/'.$employee->id.'/edit#'.$action;

        return redirect($_url)->withSuccess('Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     */
    public function destroy($id)
    {
        //
    }

    /*
     * ReHire Employee
     */
    public function rehire(Request $request, $id)
    {
        $employee = User::find($id);
        $this->checkEmployeePermissions($employee);

        $employee->rehired = Carbon::now();
        $employee->can_access_system = 1;
        $employee->status = 'enabled';
        $employee->terminated = null;
        $employee->can_rehire = null;

        $employee->save();

        $data = [
            'user_id' => $employee->id,
            'business_id' => $employee->business_id,
            'type' => 'status',
            'note' => 'Re-Hired',
        ];

        $employee->history()->create($data);

        return back()->withSuccess('Employee sucessfully re-hired');
    }

    /**
     * Shows the form to update the number of employees for the business.
     *
     * @return Response
     */
    public function number()
    {
        return view('user.employees.number')
            ->with('employeeCount', $this->user->business->additional_employees);
    }

    /**
     * Saves the number of employees to the business record.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function numberUpdate(Request $request)
    {
        $additional = $request->input('employees', 0);

        if ($additional !== $this->user->business->additional_employees) {
            $this->user->business->update(['additional_employees' => $additional]);

            session()->forget('employee_count_warning');

            //send to update policies event queue.
            UpdatePolicies::dispatch($this->user->business->id);
        }

        if ($request->input('type') === "reminder") {
            $this->user->business->update(['employee_count_reminder' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excel()
    {
        return view('user.employees.excel');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelDownload()
    {
        $filename = 'HRDirectorEmployeeImportSheet.xlsx';
        // This version of laravel does not have resource_path() available
        $filepath = base_path("resources/assets/files/$filename");

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=$filename",
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
            'Content-Length' => filesize($filepath),
        ];

        return response()->download($filepath, $filename, $headers);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function excelImport(Request $request)
    {
        $messages = [];
        $errors = [];
        if ($rawData = Excel::toArray(new EmployeesImport, $request->file('file'))) {
            $rawData = array_map(function ($el) {
                $clean = array_filter($el);
                if (count($clean) > 0) {
                    foreach (range(0, (count($el) - 1)) as $i) {
                        if (! isset($clean[$i])) {
                            $clean[$i] = null;
                        }
                    }

                    // Determine if old or new excel by number of input fields:
                    // Old (20) vs New (16) is needed because data formatting fields are in different places
                    // For each excel sheet.
                    $elementCount = count($clean);

                    //  Set dataFields associative array depending on New or Old
                    if ($elementCount == 16) {
                        $dateFields = [
                            'hired' => 13,
                            'dob' => 14,
                        ];
                    } else {
                        $dateFields = [
                            'hired' => 15,
                            'dob' => 18,
                        ];
                    }

                    ksort($clean);

                    foreach ($dateFields as $dateField => $index) {
                        if (isset($clean[$index]) && ! is_null($clean[$index])) {
                            if (is_numeric($clean[$index])) {
                                // https://stackoverflow.com/questions/11119631/excel-date-conversion-using-php-excel
                                //   The 86400 is number of seconds in a day = 24 * 60 * 60.
                                //   The 25569 is the number of days from Jan 1, 1900 to Jan 1, 1970.
                                //   Excel base date is Jan 1, 1900 and Unix is Jan 1, 1970.
                                // NOTE: The above 25569 value is straight from the article, but the
                                // resulting date was off by 1 day earlier... but:
                                //   $excel = new DateTime("1900-01-01");
                                //   $unix = new DateTime("1970-01-01");
                                //   $diff = $unix->diff($excel)->format("%a");
                                // results in 25567 *shrug* ... so using the 25568 "correct" value
                                $EXCEL_DATE = $clean[$index];
                                $UNIX_DATE = ($EXCEL_DATE - 25568) * 86400;

                                $clean[$index] = date('m/d/Y', $UNIX_DATE); // "mm/dd/yyyy" === "m/d/Y" in php format
                            } elseif (is_string($clean[$index])) {
                                $date = explode('/', $clean[$index]);
                                if (count($date) === 3) {
                                    list($m, $d, $y) = $date;
                                    if (is_numeric($m) && is_numeric($d) && is_numeric($y)) {
                                        if ($y < 100) {
                                            $y += 2000;
                                        }
                                        $clean[$index] = sprintf('%02d/%02d/%04d', $m, $d, $y);
                                    }
                                }
                            }
                        }
                    }

                    return $clean;
                }
            }, $rawData[0]);

            $columns = null;
            $rawData = array_filter($rawData);

            // Each Template has the first 5 lines of instructions.
            // Slice off and start at data row. 6
            if (isset($rawData[0][2]) && $rawData[0][2] === 'Instructions:') {
                // The Heading line of the new template is line 6
                $rawData = array_slice($rawData, 5);
                $columnsRaw = array_shift($rawData);
                // Parsing the column names.   Change from Excel to string (add '_')
                $columns = $this->prepareImportColumns($columnsRaw);
            }

            $rows = $this->excelValidateRows($rawData, $columns);

            $count = count($rows['data']);
            $messages = $rows['messages'];
            $errors = $rows['errors'];

            if ($count > 0) {
                $data['employees'] = $rows['data'];
                $data['business_id'] = $this->user->business_id;

                $results = $this->userFactory->create($data);

                if ($request->input('send_activation_email') === '1') {
                    foreach ($results['imported'] as $email) {
                        $employee = User::where('email', $email)->first();
                        $this->resendAuthorizationEmail($employee);
                    }
                }

                if (count($results['imported']) > 0) {
                    $messages[] = count($results['imported']).' New employee(s) created.';
                }

                foreach ($results['skipped'] as $k => $v) {
                    $errors[] = 'Skipped '.$k.": $v";
                }
            } else {
                $errors = isset($rows['errors']) && ! empty($rows['errors']) ? $rows['errors'] :
                    ['An error has occurred. Please check your Import File and try again.'];
            }
        }

        return redirect('/user/employees')
            ->withErrors($errors)
            ->withSuccess(implode('<br/>', $messages));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function additionalEmployeesUpdate(Request $request)
    {
        $this->business->additional_employees = $request->input('additional_employees', 0);
        if ($this->business->additional_employees == '') {
            $this->business->additional_employees = 0;
        }

        $this->business->save();

        return redirect('/user/employees');
    }

    /**
     * Delete attendance record
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAttendanceRecord($id) {

        $result = Attendance::destroy($id);
        if($result) {
            return response()->json(['status' => 'success', 'data' => $id]);
        }
        return response()->json(['status' => 'danger', 'data' => $id]);

    }

    /**
     * Helper method - checks whether the current logged in user can see or edit
     * the employee record. Currently, this only prevents users from editing
     * employees from other businesses. Throws a 403 if not authorized.
     *
     * @param \Bentericksen\Employees\Employee $employee The Employee object
     */
    private function checkEmployeePermissions($employee)
    {
        // check that the loaded employee is in the current business
        // (for admins/consultants using the "view as" feature, this check will
        // only pass if the employee belongs to the *currently viewed* business.
        if ($employee->business_id !== $this->businessId) {
            abort(403, 'You do not have permission to see this content.');
        }
    }

    /**
     * Validating rows for Excel Import.
     *
     * @param $rows
     * @param $columns
     *
     * @return array
     */
    private function excelValidateRows($rows, $columns)
    {
        $required_fields = [
            'first_name',
            'last_name',
            'email',
            'address_1',
            'city',
            'state',
            'zipcode',
            'phone1_type',
            'phone1_number',
            'hired',
            'date_of_birth',
            'send_activation_email',
        ];

        $data = [];
        $errors = [];
        $messages = [];

        if ($columns) {
            $rows = array_map(function ($el) use ($columns) {
                return array_combine($columns, $el);
            }, $rows);
        }
        foreach ($rows as $index => $row) {
            $valid = true;
            $testRow = $row;
            // eliminating all null values
            foreach ($testRow as $key => $value) {
                if (! $value) {
                    unset($testRow[$key]);
                }
            }

            // checking if $row has values
            if (! empty($testRow)) {
                foreach ($required_fields as $required) {
                    if (empty($row[$required])) {
                        $valid = false;
                        $errors[] = 'Row '.$index.': Missing required field';
                    }
                }
            } else {
                $valid = false;
            }

            if (! $valid) {
                continue;
            }

            if (isset($row[0])) {
                unset($row[0]);
            }

            $data[] = $row;
        }

        return [
            'data' => $data,
            'errors' => $errors,
            'messages' => $messages,
        ];
    }

    /**
     * Employee Update: Termination.
     *
     * @param User $employee
     * @param $data array
     *
     * @return mixed
     */
    private function updateEmployeeTerminate(User $employee, $data)
    {
        $employee->terminated = Carbon::parse($data['date']);
        $employee->can_access_system = 0;
        $employee->status = 'terminated';
        $employee->can_rehire = $data['rehire'] == 'yes';
        $employee->rehired = null;

        $history_data = [
            'user_id' => $employee->id,
            'business_id' => $employee->business_id,
            'type' => 'Termination',
            'note' => $data['date'].' | '.$data['type'].' | Eligible Rehire: '.$data['rehire'],
            'status' => '',
        ];

        if (! empty($data['reason'])) {
            $history_data['note'] .= ' | '.$data['reason'];
        }

        $employee->history()->create($history_data);

        return $employee->save();
    }

    /**
     * Updating Salary Record.
     * @param User $employee
     * @param $data
     */
    private function updateEmployeeSalary(User $employee, $data)
    {
        unset($data['current_salary']);
        unset($data['current_rate']);

        $effective_at = Carbon::createFromTimestamp(strtotime($data['effective_at']));
        $data['salary'] = ! empty($data['salary']) ? str_replace(',', '', $data['salary']) : '0.00';

        if ($effective_at->gt(Carbon::today())) {
            $data['user_id'] = $employee->id;
            $salaryUpdate = new SalaryUpdates($data);
            $salaryUpdate->save();
        } else {
            if ($employee->salary) {
                $employee->salary->update($data);
            } else {
                $employee->salary()->create($data);
            }
        }
    }

    /**
     * Updating Classification record.
     * @param User $employee
     * @param $data
     */
    private function updateEmployeeClassification(User $employee, $data)
    {
        if (empty($data['new_classification_id'])) {
            return;
        }

        if ($data['new_classification_id'] == 'other') {
            $classification = Classification::create([
                'name' => $data['new_classification_name'],
                'business_id' => $employee->business_id,
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 1,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 8,
                'maximum_hours_interval' => 'day',
            ]);
            $classification_id = $classification->id;
        } else {
            $classification_id = $data['new_classification_id'];
        }

        $effective_at = Carbon::createFromTimestamp(strtotime($data['classification_date']));

        if ($effective_at->gt(Carbon::now())) {

            $data = $this->prepareClassificationUpdateData($data, $employee, $classification_id);
            $update = new ClassificationUpdates($data);
            $update->save();
            $employee->update(['classification_id' => $data['classification_id']]);

            return;

        }

        $update = ClassificationUpdates::where('classification_id', $classification_id)->latest()->first();

        // First time classification updates won't have a ClassificationUpdate
        if(isset($update)) {
            $update->update(['effective_at' => $effective_at]);
        } else {

            $data = $this->prepareClassificationUpdateData($data, $employee, $classification_id);
            $update = new ClassificationUpdates($data);
            $update->save();
        }

        $employee->update(['classification_id' => $classification_id]);

    }

    /**
     * Prepares data for creating a ClassificationUpdate
     * @param $data
     * @param $employee
     * @param $classification_id
     * @return mixed
     */
    private function prepareClassificationUpdateData($data, $employee, $classification_id) {

        $data['user_id'] = $employee->id;
        $data['classification_id'] = $classification_id;
        $data['effective_at'] = $data['classification_date'];

        unset($data['new_classification_id']);
        unset($data['classification_date']);

        return $data;

    }

    /**
     * Updating User general info (Users table columns).
     * @param $employee
     * @param $data
     *
     * @return mixed
     */
    private function updateInfo($employee, $data)
    {
        return $employee->update($data);
    }

    /**
     * Updating Emergency Contacts.
     *
     * @param User $employee
     * @param $data
     */
    private function updateEmergencyContacts(User $employee, $data)
    {
        $primary = $data['primary'];
        $secondary = $data['secondary'];

        $primary['is_primary'] = 1;

        if (array_key_exists('id', $primary)) {
            EmergencyContact::where('id', $primary['id'])->first()->update($primary);
        } else {
            $employee->emergencyContacts()->create($primary);
        }

        if (array_key_exists('id', $secondary)) {
            EmergencyContact::find($secondary['id'])->update($secondary);
        } else {
            $employee->emergencyContacts()->create($secondary);
        }
    }

    /**
     * Updating drivers license record.
     * @param User $employee
     * @param $data
     */
    private function updateDriversLicense(User $employee, $data)
    {
        if (array_key_exists('id', $data)) {
            DriversLicense::find($data['id'])->update($data);
        } else {
            $employee->driversLicense()->create($data);
        }
    }

    /**
     * Updating licensures/certifications record.
     *
     * @param User $employee
     * @param $data
     */
    private function updateLicensures(User $employee, $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'new') {
                $employee->addLicensures($value);
                continue;
            }

            if ($key == 'remove') {
                $employee->removeLicensures($value);
                continue;
            }

            if ($key == 'update') {
                $employee->updateLicensures($value);
                continue;
            }
        }
    }

    /**
     * Updating/Creating attendance record.
     * @param User $employee
     * @param $data
     *
     * @return mixed
     */
    private function updateAttendance(User $employee, $data)
    {
        return $employee->attendance()->create($data);
    }

    /**
     * Saves time off requests to the database.
     *
     * @param User $employee
     * @param $data
     */
    private function updateLeaveOfAbsence(User $employee, $data)
    {
        $data['type'] = $this->types[$data['type']];
        $data['request_type'] = 'leave';

        if (! empty($data['id'])) {
            TimeOff::find($data['id'])->update($data);
        } else {
            $data['request_pto_time'] = 0;
            $employee->timeOff()->create($data);
        }
    }

    /**
     * Update User's paperwork.
     *
     * @param User $employee
     * @param $data
     *
     * @return
     */
    private function updatePaperwork(User $employee, $data)
    {
        return $employee->paperwork()->sync($data['paperwork_id']);
    }

    /**
     * Updating authorization record.
     * @param User $employee
     * @param $data
     */
    private function updateAuthorization(User $employee, $data)
    {
        $employee->update(['can_access_system' => $data['can_access_system']]);
        $employee->roles()->sync($data['role_id']);
        PerformStreamdentUserProcess::dispatch($employee, 'toggle');
    }

    /**
     * Resend password reset link.
     * @param User $employee
     */
    private function resendAuthorizationEmail(User $employee)
    {
        session(['activation_password_reset' => 'activation_password_reset']);
        $response = Password::sendResetLink(['email' => $employee->email], function (Message $message) {
            $message->subject('Account Activation');
        });

        // Create Mailable record to create account activation event in outgoing_emails table.
        $attributes = [
            'subject' => 'Account Activation Email',
            'user_id' => $employee->id,
            'to' => $employee->email,
            'related_type' => self::class,
            'response' => $response,
        ];

        $mailable = new AccountActivationEmail($attributes);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->saveFromMailable();
    }

    /**
     * Parsing Excel Import column names.
     * @param $columnsRaw
     *
     * @return array
     */
    protected function prepareImportColumns($columnsRaw)
    {
        return array_map(function ($el) {
            $columnName = preg_replace('/[^\da-z]/i', '_', $el);
            $columnName = str_replace(['____', '___', '__'], '_', $columnName);

            // Removing First character when '_'
            while (substr($columnName, 0, 1) == '_') {
                $columnName = Str::replaceFirst('_', '', $columnName);
            }

            // Removing last character when '_'
            while (substr($columnName, -1) == '_') {
                $columnName = Str::replaceLast('_', '', $columnName);
            }

            return strtolower($columnName);
        }, $columnsRaw);
    }
}
