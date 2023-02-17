<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Bentericksen\ViewAs\ViewAs;
use DB;
use Illuminate\Http\Request;

class LicensureController extends Controller
{
    private $user;
    private $business;
    private $licensure;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());
        $this->business = Business::find($this->user->business_id);

        $this->licensure = DB::table('licensure_certifications')->where('business_id', $this->business->id)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $licensure = $this->licensure;

        return view('user.licensure_certifications.index', compact(['licensure']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.licensure_certifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [
            'business_id' => $this->business->id,
            'name'	=> $request->input('name'),
            'status' => 'active',
        ];

        DB::table('licensure_certifications')->insert($data);

        return redirect('/user/licensure-certifications');
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
        $licensure = DB::table('licensure_certifications')
                        ->where('business_id', $this->business->id)
                        ->where('id', $id)
                        ->first();

        return view('user.licensure_certifications.edit', compact(['licensure']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $licensure = DB::table('licensure_certifications')
                        ->where('business_id', $this->business->id)
                        ->where('id', $id)
                        ->update(['name' => $request->input('name')]);

        return redirect('/user/licensure-certifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::table('licensure_certifications')->where('business_id', $this->business->id)->where('id', $id)->delete();

        return redirect('/user/licensure-certifications');
    }
}
