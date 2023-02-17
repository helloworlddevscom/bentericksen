<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobDescriptionRequest;
use App\JobDescription;
use App\User;
use Auth;
use Bentericksen\PrintServices\JobPrintService;
use Bentericksen\ViewAs\ViewAs;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobDescriptionController extends Controller
{
    protected $jobs;

    private $businessId;

    private $business;

    private $user;

    private $jobDescriptions;

    private $industry = [
        ' - Select One - ',
        'Dental',
        'Medical',
        'Veterinarian',
        'Commercial',
    ];

    private $subtypes = [
        'CPA',
        'Mechanic',
        'Restaurant',
        'Retail',
        'Chiropractic',
        'Cosmetic Surgery',
        'Internal Medicine',
        'Optometry',
        'Pediatric',
        'Surgical',
        'General',
        'Orthodontist',
    ];

    public function __construct(JobDescription $jobs, ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());

        $this->businessId = $this->user->business_id;
        $this->business = $this->user->business;

        $this->jobs = $jobs;
        $this->jobDescriptions = $this->jobs->whereIn('business_id', [
            0,
            $this->business->id,
        ])
            ->whereIn('industry', [$this->business->type, ''])
            ->where('id', '!=', 33)
            ->get();
        $outline = $this->jobs->where('id', 33)->first();

        if ($outline) {
            $this->jobDescriptions->prepend($outline);
        }

        foreach ($this->jobDescriptions as $key => $jobDescription) {
            $subTypes = json_decode($jobDescription->subtype);
            if (! is_null($subTypes)) {
                if (! in_array($this->business->subtype, $subTypes)) {
                    unset($this->jobDescriptions[$key]);
                }
            }
        }
    }

    /**
     * Display a listing of job descriptions.
     *
     * @return Response
     */
    public function index()
    {
        foreach ($this->jobDescriptions as $key => $jobDescription) {
            if (! $jobDescription->is_default) {
                $this->jobDescriptions[$key]->assigned = User::whereIn('id', DB::table('user_job_description')
                    ->where('job_description_id', $jobDescription->id)
                    ->pluck('user_id'))->get();
            }
        }

        return view('user.job_descriptions.index')->with('jobDescriptions', $this->jobDescriptions);
    }

    /**
     * Shows the job description detail.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $job = JobDescription::findOrFail($id);
        $this->checkPermissions($job);

        if ($request->ajax()) {
            return $job;
        }

        return redirect('/user/job-descriptions');
    }

    /**
     * Show the form for creating a new job description.
     *
     * @return Response
     */
    public function create()
    {
        $employees = $this->business->users()->where('status', 'enabled')->get();

        return view('user.job_descriptions.create', [
            'business' => $this->business,
            'jobDescriptions' => $this->getJobDescriptionsArray(),
            'employees' => $employees,
        ]);
    }

    /**
     * Show the "create" form, pre-filled with the data from an
     * existing job description.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function duplicate($id)
    {
        $job = JobDescription::findOrFail($id);
        $this->checkPermissions($job);

        return view('user.job_descriptions.create', [
            'business' => $this->business,
            'job' => $job,
            'jobDescriptions' => $this->getJobDescriptionsArray(),
            'employees' => $this->business->users,
        ]);
    }

    /**
     * Store a newly created job description in the DB.
     * (This is where the create/duplicate form submits data.).
     *
     * @param \App\Http\Requests\JobDescriptionRequest $request
     *
     * @return Response
     */
    public function store(JobDescriptionRequest $request)
    {
        $data = $request->except(['_token', 'type']);

        if (! isset($data['subtype'])) {
            $data['subtype'] = null;
        }

        $data['subtype'] = json_encode($data['subtype']);
        $data['is_default'] = 0;
        $data['business_id'] = $this->business->id;

        $employees = [];
        if (isset($data['employees'])) {
            $employees = $data['employees'];
            unset($data['employees']);
        }

        $job = $this->jobs->create($data);

        foreach ($employees as $employee) {
            DB::table('user_job_description')
                ->where('user_id', $employee)
                ->where('job_description_id', $job->id)
                ->delete();
            DB::table('user_job_description')->insert([
                'user_id' => $employee,
                'job_description_id' => $job->id,
            ]);
        }

        return redirect('/user/job-descriptions');
    }

    /**
     * Show the form for editing a job description.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $job = JobDescription::findOrFail($id);
        $this->checkPermissions($job);

        $assigned = DB::table('user_job_description')
            ->where('job_description_id', $id)
            ->pluck('user_id')
            ->toArray();

        $employees = $this->business->users()->where('status', 'enabled')->get();

        return view('user.job_descriptions.edit', [
            'industry' => $this->industry,
            'subtypes' => $this->subtypes,
            'job' => $job,
            'assigned' => $assigned,
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in the database.
     * This is where the update form submits data.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $job = JobDescription::findOrFail($id);
        $this->checkPermissions($job);

        $data = $request->except('_method', '_token');

        DB::table('user_job_description')
            ->where('job_description_id', $id)
            ->delete();

        if (isset($data['employees'])) {
            foreach ($data['employees'] as $employee) {
                DB::table('user_job_description')
                    ->insert([
                        'user_id' => $employee,
                        'job_description_id' => $id,
                    ]);
            }
        }

        unset($data['employees']);

        $job->update($data);

        return redirect('/user/job-descriptions');
    }

    /**
     * Delete the specified resource from the DB.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $job = JobDescription::findOrFail($id);

        if ($job->business_id === $this->businessId && $job->business_id !== 0) {
            $job->delete();
        }

        return redirect('/user/job-descriptions');
    }

    /**
     * Generates a PDF of the job description.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function printJob($id)
    {
        $job = JobDescription::findOrFail($id);
        $this->checkPermissions($job);

        $filename = Str::random(40);
        $jobPrintService = new JobPrintService($job, $filename);
        $jobPrintService->generate();

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ];

        return response()->make(file_get_contents(storage_path('bentericksen/temp/'.$filename)), 200, $headers);
    }

    /**
     * Helper method - checks whether the current logged in user can see or edit
     * the job description. Throws a 403 if not authorized.
     *
     * @param \App\JobDescription $job_description
     */
    private function checkPermissions($job_description)
    {
        // check that the loaded employee is in the current business
        // (for admins/consultants using the "view as" feature, this check will
        // only pass if the employee belongs to the *currently viewed* business.
        if ($job_description->business_id !== 0 &&
            $job_description->business_id !== $this->businessId) {
            abort(403, 'You do not have permission to see this content.');
        }
    }

    /**
     * Returns Job Descriptions array for create/update/duplicate forms.
     *
     * @return array
     */
    private function getJobDescriptionsArray()
    {
        $jobDescriptions = [
            'default' => '- Select One -',
            'blank' => 'Blank',
        ];

        foreach ($this->jobDescriptions as $jobDescription) {
            if ($jobDescription->business_id == 0) {
                $jobDescriptions[$jobDescription->id] = $jobDescription->name;
            }
        }

        return $jobDescriptions;
    }
}
