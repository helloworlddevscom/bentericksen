<?php

namespace App\Http\Controllers\Admin;

use App\Business;
use App\BusinessJobDescription;
use App\EmergencyContact;
use App\Facades\PaymentService as PayService;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewBusinessRequest;
use App\JobDescription;
use App\Mail\AccountActivationEmail;
use App\Mail\AccountUpdateEmail;
use App\OutgoingEmail;
use App\Role;
use App\User;
use Auth;
use Bentericksen\Admin\CreateBusiness;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\PaymentService;
use Bentericksen\Settings\Industries;
use Bentericksen\Settings\States;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as NewRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Validator;
use App\Facades\StreamdentService;
use App\Jobs\UpdatePolicies;

class BusinessController extends Controller
{
    private $phoneTypes = [];

    private $types = [];

    private $subTypes = [];

    private $statuses = [];

    private $user;

    private $admin;

    private $consultants;

    private $asa_types;

    private $states;

    private $industries;

    public function __construct(ViewAs $viewAs, User $user)
    {
        if (! $viewAs->getUserId()) {
            return $this;
        }

        $this->admin = auth()->user();
        $this->states = (new States)->states();
        $this->industries = (new Industries)->getIndustries();

        $this->user = $user;

        $this->phoneTypes = [
            'cell' => 'Cell',
            'office' => 'Office',
            'home' => 'Home',
        ];

        $this->types = [
            'dental' => 'Dental',
            'medical' => 'Medical',
            'veterinarian' => 'Veterinarian',
            'commercial' => 'Commercial',
        ];

        // deprecated. Should be grabbing from a php object in settings. Leaving for now.
        $this->subTypes = [
            'cpa' => 'Other',
            'mechanic' => 'Other',
            'restaurant' => 'Other',
            'retail' => 'Other',
            'chiropractic' => 'Other',
            'cosmetic_surgery' => 'Other',
            'internal_medicine' => 'Other',
            'optometry' => 'Other',
            'pediatric' => 'Other',
            'surgical' => 'Other',
            'general' => 'Other',
            'endodontic' => 'Other',
            'cosmetic' => 'Other',
            'financial_services' => 'Other',
            'lab' => 'Other',
            'oral_maxillofacial' => 'Other',
            'oral_surgery' => 'Other',
            'orthodontic' => 'Other',
            'periodontic' => 'Other',
            'pedodontic' => 'Other',
            'prosthodontics' => 'Other',
        ];

        $this->statuses = [
            'active' => 'Active',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
            'renewed' => 'Renewed',
        ];

        $this->asa_types = [
            'limited' => 'Limited',
            'comprehensive' => 'Comprehensive',
            'monthly-1-14' => 'Monthly 1-14',
            'monthly-15-30' => 'Monthly 15-30',
            'monthly-31-49' => 'Monthly 31-49',
            'monthly-50' => 'Monthly 50+',
            'annual-1-14' => 'Annual 1-14',
            'annual-15-30' => 'Annual 15-30',
            'annual-31-49' => 'Annual 31-49',
            'annual-50-99' => 'Annual 50-99',
            'annual-100-249' => 'Annual 100-249',
            'annual-250-499' => 'Annual 250-499',
            'annual-500' => 'Annual 500+',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(NewRequest $request)
    {
      return $this->inertia('Admin/Business/Index', [
        '_response' => $this->getBusinesses($request)
      ]);
    }

    public function getBusinesses(NewRequest $request)
    {
        $businessNameSearch = $request->input('business_name');
        $contactNameSearch = $request->input('contact_name');
        $sort = $request->input('sort') ?? 'business.name';
        $sortOrder = $request->input('sort_order') ?? 'asc';
        $from = $request->input('from');
        $to = $request->input('to');

        $businesses = Business::select([
            'business.id',
            'business.name',
            'business.state',
            'business.hrdirector_enabled',
            'business.bonuspro_enabled',
            'business_asas.expiration',
            'users.first_name',
            'users.last_name',
        ])
        ->join('business_asas', 'business.id', '=', 'business_asas.business_id')
        ->join('users', 'business.primary_user_id', '=', 'users.id');

        if (! empty($businessNameSearch)) {
            $businesses = $businesses->where('business.name', 'like', "%$businessNameSearch%");
        }

        if (! empty($contactNameSearch)) {
            $businesses = $businesses->where(function ($query) use ($contactNameSearch) {
                $query->where('users.first_name', 'like', "%$contactNameSearch%")
                ->orWhere('users.last_name', 'like', "%$contactNameSearch%");
            });
        }

        if (! empty($to) && ! empty($from)) {
            $businesses = $businesses->whereBetween('business_asas.expiration', [$from, $to]);
        }

        if (! empty($sort) && ! empty($sortOrder)) {
            $businesses = $businesses
                ->orderBy($sort, $sortOrder);
        }

        $businesses = $businesses
            ->paginate(10);

        return $businesses;
    }

    /**
     * Show the form for creating a new Business.
     *
     * @return Response
     */
    public function create()
    {
        $this->initializeConsultants();

        $roles = Role::where('id', '>', 1)
            ->pluck('display_name', 'id')
            ->toArray();

        $industryArray = ['' => '- Select One -'];
        foreach ($this->industries as $type => $data) {
            $industryArray[$type] = $data['title'];
        }

        return view('admin.business.create')
            ->with([
                'states' => $this->states,
                'phoneTypes' => $this->phoneTypes,
                'consultants' => $this->consultants,
                'statuses' => $this->statuses,
                'roles' => ['' => '- Select One -'] + $roles,
                'industryArray' => $industryArray,
                'industries' => $this->industries,
                'asaTypes' => ['' => '- Select One -'] + $this->asa_types,
            ]);
    }

    /**
     * Saves a new Business to the database. The create form posts here.
     *
     * @param \App\Http\Requests\NewBusinessRequest $request
     *
     * @return Response
     */
    public function store(NewBusinessRequest $request, Response $response)
    {
        //Need to move this into its own models. Prototyped code that has been sitting too long.
        $userValues = [
            'first_name' => $request->input('primary_first_name'),
            'middle_name' => $request->input('primary_middle_name'),
            'last_name' => $request->input('primary_last_name'),
            'prefix' => $request->input('primary_prefix'),
            'suffix' => $request->input('primary_suffix'),
            'email' => $request->input('primary_email'),
            'password' => bcrypt($request->input('owner_login_password')),
        ];

        //create user
        $primaryUser = User::create($userValues);
        $primaryUser->attachRole($request->input('primary_role'));

        $businessFactory = new CreateBusiness;
        $businessFactory->load($request, $primaryUser);
        $business = $businessFactory->save();

        $primaryUser->business_id = $business->id;
        $primaryUser->hired = '1970-01-01 00:00:00';
        $primaryUser->dob = '1970-01-01 00:00:00';
        $primaryUser->save();

        // Create Business' Stripe Account
        PaymentService::createStripeAccount($business, $response);

        if (! $request->input('hrdirector_enabled')) {
            return redirect('/admin/business');
        }

        $jobDescriptionDefaults = JobDescription::where('is_default', 1)->get();
        foreach ($jobDescriptionDefaults as $job) {
            $businessJobDescription = new BusinessJobDescription();
            $businessJobDescription->business_id = $business->id;
            $businessJobDescription->job_description_id = $job->id;
            $businessJobDescription->save();
        }

        //create drivers license blank
        DB::table('driver_licenses')->insert(['user_id' => $primaryUser->id]);

        for ($i = 0; $i < 2; $i++) {
            $emergencyContact = new EmergencyContact;
            $emergencyContact->user_id = $primaryUser->id;
            $emergencyContact->name = ' ';
            $emergencyContact->phone1 = ' ';
            $emergencyContact->phone1_type = 'cell';
            $emergencyContact->phone2 = ' ';
            $emergencyContact->phone2_type = 'cell';
            $emergencyContact->phone3 = ' ';
            $emergencyContact->phone3_type = 'cell';
            $emergencyContact->relationship = '';
            $emergencyContact->is_primary = ($i == 0 ? 1 : 0);
            $emergencyContact->save();
        }

        if ($request->input('asa')['type'] != '') {
            $asa = [
                'business_id' => $business->id,
                'type' => $request->input('asa')['type'],
                'status' => 'active',
            ];

            if (isset($request->input('asa')['expiration']) && $request->input('asa')['expiration'] != '') {
                $asa['expiration'] = Carbon::createFromFormat('m/d/Y', $request->input('asa')['expiration'])
                        ->format('Y-m-d').' 00:00:00';
            }

            DB::table('business_asas')->insert($asa);
            $asaId = DB::table('business_asas')
                ->where('business_id', $business->id)
                ->pluck('id');
            DB::table('business')
                ->where('id', $business->id)
                ->update(['asa_id' => $asaId]);
        }

        UpdatePolicies::dispatch($business->id);

        \Session::flash('success', "Business {$business->name} was created successfully.");

        if (! is_null($request->input('action')) && $request->input('action') == 'view-as') {
            return redirect('/admin/business/'.$business->id.'/view-as');
        }

        if ($request->input('send_activation_email') === '1') {
            session(['activation_password_reset' => 'activation_password_reset']);

            $email = $request->input('primary_email');
            $response = Password::sendResetLink(['email' => $email]);

            // Create Mailable record to create account activation event in outgoing_emails table.
            $attributes = [
                'subject' => 'Account Activation Email',
                'user_id' => null,
                'to' => $email,
                'related_type' => self::class,
                'response' => $response,
            ];

            $mailable = new AccountActivationEmail($attributes);
            $mailer = new OutgoingEmail([], $mailable);
            $mailer->saveFromMailable();
        }

        return redirect('/admin/business');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect('/admin');
    }

    /**
     * Show the form for editing the specified Business.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->initializeConsultants();

        $roles = Role::where('id', '>', 1)
            ->pluck('display_name', 'id')
            ->toArray();
        $business = Business::find($id)->load(['asa', 'users']);
        $business->primary = User::find($business->primary_user_id);

        $industryArray = ['' => '- Select One -'];
        foreach ($this->industries as $type => $data) {
            $industryArray[$type] = $data['title'];
        }

        if (! $business->asa) {
            $business->asa = new \StdClass;
            $business->asa->type = '';
            $business->asa->expiration = '';
        }

        $employeeNames = ['- Select One -'];
        foreach ($business->users as $employee) {
            $employeeNames[$employee->id] = $employee->full_name;
        }

        return view('admin.business.edit')
            ->with([
                'business' => $business,
                'states' => $this->states,
                'phoneTypes' => $this->phoneTypes,
                'consultants' => $this->consultants,
                'statuses' => $this->statuses,
                'roles' => ['' => '- Select One -'] + $roles,
                'industryArray' => $industryArray,
                'industries' => $this->industries,
                'employeeNames' => $employeeNames,
                'asaTypes' => ['' => '- Select One -'] + $this->asa_types,
            ]);
    }

    /**
     * Saves changes to the Business. The edit form posts here.
     *
     * @param \App\Http\Requests\NewBusinessRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(NewBusinessRequest $request, $id)
    {
        /** @var Business $business */
        $business = Business::findOrFail($id);
        $newOwner = null;

        foreach ($request->all() as $inputKey => $input) {
            $data[$inputKey] = $input;
        }

        unset($data['_method']);
        unset($data['_token']);
        unset($data['business_asa_expiration_date']);
        unset($data['action']);
        unset($data['asa']);

        if (isset($data['new_primary_user'])) {
            $newOwner = $request->input('new_primary_user');
            unset($data['new_primary_user']);
        }

        // Update user
        $userValues = [
            'first_name',
            'middle_name',
            'last_name',
            'prefix',
            'suffix',
            'email',
            'role',
        ];

        $primary_user = User::find($business->primary_user_id)->load('roles');

        foreach ($userValues as $userValue) {
            if ($userValue == 'role') {
                if ($primary_user->roles->first()->id !== $data["primary_{$userValue}"]) {
                    $primary_user->roles()
                        ->detach($primary_user->roles->first()->id);
                    $primary_user->roles()
                        ->attach($data["primary_{$userValue}"]);
                }
            } else {
                $primary_user->{$userValue} = $data["primary_{$userValue}"];
            }
            unset($data["primary_{$userValue}"]);
        }

        if (! empty($data['owner_password'])) {
            $primary_user->password = bcrypt($data['owner_password']);
            unset($data['owner_password']);
        }

        $primary_user->save();

        if (isset($data['finalized']) && $business->finalized != $data['finalized']) {
            if ($business->hasPendingPolicies()) {
                $validator = Validator::make($request->all(), []);
                $validator->after(function ($validator) {
                    $validator->errors()
                        ->add('finalized', 'Whoops! Looks like this client still has pending policy changes.');
                });

                return redirect('admin/business/'.$business->id.'/edit')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($newOwner && (int) $newOwner !== $business->primary_user_id) {
            $data['primary_user_id'] = $newOwner;
            $business->switchPrimaryUser($newOwner);
        }

        $business->update($data);

        // Resetting accepted_terms date to 0000-00-00 00:00:00
        if ($data['status'] == 'renewed') {
            foreach ($business->hiredUsers as $user) {
                $user->accepted_terms = '0000-00-00 00:00:00';
                $user->status = 'enabled';
                $user->save();
            }
        }

        $asaData = $request->input('asa');

        if ($business->asa) {
            $business->asa->update($asaData);
        } else {
            $business->asa()->create($asaData);
        }

        $business->save();

        \Session::flash('success', "Business {$business->name} was updated successfully.");

        if (! is_null($request->input('action')) && $request->input('action') == 'view-as') {
            return redirect('/admin/business/'.$business->id.'/view-as');
        }

        // Send an e-mail update to info@bentericksen.com if not an admin
        if (! Auth::user()->hasRole('admin')) {
            $this->sendBusinessUpdateNotification($business);
        }

        return redirect('/admin');
    }

    /**
     * Sends a notification when the business information has been updated.
     *
     * @param Business $business
     */
    private function sendBusinessUpdateNotification($business)
    {
        $mailable = new AccountUpdateEmail(Auth::user(), $business);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->to_address = 'info@bentericksen.com';
        $mailer->user_id = User::where('email', 'info@bentericksen.com')->first()->id;
        $mailer->send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function viewAs($id)
    {
        $business = Business::findOrFail($id);

        session()->put('viewAs', $business->primary_user_id);
        session()->put('viewAsRole', 'admin');

        return redirect()->route('user.dashboard');
    }

    public function viewAsUser($id)
    {
        $user = User::findOrFail($id);

        session()->put('viewAs', $user->id);
        session()->put('viewAsRole', 'admin');

        return redirect()->route('user.dashboard');
    }

    /**
     * @param NewRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function duplicate(NewRequest $request)
    {
        return view('admin.business.duplicate_entry');
    }

    /**
     * Initializing Consultants.
     *
     * @return $this
     */
    private function initializeConsultants()
    {
        $this->consultants = [];

        $consultants = User::with([
            'roles' => function ($q) {
                $q->where('role_id', '=', 4);
            },
        ])->get()->sortBy('first_name');

        $this->consultants = [];

        foreach ($consultants as $consultant) {
            if ($consultant->roles->count() == 0) {
                continue;
            }

            array_push($this->consultants, $consultant);
        }

        return $this;
    }
}
