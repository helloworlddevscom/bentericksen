<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected $categories;

    public function __construct()
    {
        $this->categories = new Category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $last = $this->categories->where('grouping', $request->input('grouping'))->orderBy('order', 'desc')->first();
        if (is_null($last)) {
            $order = 0;
        } else {
            $order = $last++;
        }

        $data = [
            'business_id' => 0,
            'name' 		  => $request->input('name'),
            'grouping'	  => $request->input('grouping'),
            'order'		  => $order,
        ];

        $this->categories->create($data);

        return redirect('admin/'.$request->input('grouping'));
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
     * @param  int  $id
     * @return Response
     */
    public function update($id)
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
}
