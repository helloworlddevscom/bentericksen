<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\JobTitle;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    private $user;
    private $businessId;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());

        $this->businessId = $this->user->business_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $jobTitles = JobTitle::where('business_id', $this->businessId)->get();

        return view('user.job_titles.index', compact(['jobTitles']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.job_titles.create', compact([]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $jobTitle = new JobTitle();
        $jobTitle->business_id = $this->businessId;
        $jobTitle->name = $request->input('name');
        $jobTitle->save();

        return redirect()->route('user.job-titles.index');
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
        $job = JobTitle::findOrFail($id);

        return view('user.job_titles.edit', compact(['job']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        $jobTitle->name = $request->input('name');
        $jobTitle->save();

        return redirect()->route('user.job-titles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        $jobTitle->delete();

        return redirect()->route('user.job-titles.index');
    }
}
