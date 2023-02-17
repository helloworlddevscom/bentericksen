<?php

namespace App\Http\Controllers\Admin;

use App\BusinessJobDescription;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\JobDescription;
use Bentericksen\Settings\Industries;
use DB;
use Illuminate\Http\Request;

class JobDescriptionController extends Controller
{
    protected $jobs;

    public function __construct(JobDescription $jobs)
    {
        $this->jobs = $jobs;
        $this->industries = (new Industries)->getIndustries();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $jobDescriptions = $this->jobs->where('business_id', 0)->get();

        foreach ($jobDescriptions as $key => $value) {
            $temp = [];
            if (! is_null(json_decode($value->subtype))) {
                foreach (json_decode($value->subtype) as $ke => $val) {
                    if (isset($this->industries[$value->industry]['subtype'][$val])) {
                        $temp[] = $this->industries[$value->industry]['subtype'][$val];
                    }
                }
            }

            $jobDescriptions[$key]->subtypes = (empty($temp) ? '' : implode(', ', $temp));

            $jobDescriptions[$key]->subtypes_string = implode(', ', $temp);
        }

        $industries = $this->industries;

        return view('admin.job_description.index', compact(['jobDescriptions', 'industries']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $industries = $this->industries;

        return view('admin.job_description.create', compact(['industries']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (! isset($data['subtype'])) {
            $data['subtype'] = null;
        }
        $data['subtype'] = json_encode($data['subtype']);
        $data['is_default'] = 1;

        $this->jobs->create($data);

        return redirect('/admin/job-descriptions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $jobDescription = JobDescription::findOrFail($id);
        $industries = $this->industries;

        return view('admin.job_description.edit', compact(['jobDescription', 'industries']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $jobDescription = jobDescription::find($id);
        if (is_null($jobDescription)) {
            return redirect('/admin/job-descriptions');
        }

        $jobDescription->name = $request->input('name');
        $jobDescription->industry = $request->input('industry');
        $jobDescription->description = $request->input('description');
        $jobDescription->subtype = json_encode($request->input('subtype'));
        $jobDescription->save();

        return redirect('/admin/job-descriptions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    /*
   public function destroy($id)
   {
       JobDescription::destroy($id);

       BusinessJobDescription::where('job_description_id', $id)->delete();

       return redirect()->route('admin.job-descriptions.index');
   }
   */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $jobDescription = DB::table('job_descriptions')->where('id', $id)->delete();

        return redirect('/admin/job-descriptions');
    }
}
