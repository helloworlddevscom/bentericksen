<?php

namespace App\Http\Controllers\Admin;

use App\Help;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\HelpRequest;
use Bentericksen\Settings\HelpSections;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    protected $help;
    private $sections;

    public function __construct(Help $help)
    {
        $this->help = $help;
        $this->sections = new HelpSections;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $helps = $this->help->all();

        $sections = $this->sections;

        return view('admin.help.index', compact(['helps', 'sections']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $sections = $this->sections;

        return view('admin.help.create', compact(['sections']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(HelpRequest $request)
    {
        $this->help->create($request->all());

        return redirect('/admin/help');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $helps = $this->help->findOrFail($id);

        $sections = $this->sections;

        return view('admin.help.edit', compact(['helps', 'sections']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(HelpRequest $request, $id)
    {
        $help = $this->help->findOrFail($id);
        $help->update($request->all());

        return redirect('admin/help');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $help = $this->help->find($id);
        if (! is_null($help)) {
            $help->delete();
        }

        return redirect('/admin/help');
    }
}
