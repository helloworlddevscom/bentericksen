<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Form;
use App\FormTemplate;
use App\Http\Controllers\Controller;
use Bentericksen\PrintServices\FormPrintPreviewService;
use Bentericksen\Settings\States;
use Bentericksen\Settings\Industries;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;


class FormsController extends Controller
{
    protected $forms;
    private $categories;
    private $states;
    private $industries;

    public function __construct(FormTemplate $forms)
    {
        $this->forms = $forms;
        $this->categories = Category::where('grouping', 'forms')->get();
        $this->states = (new States)->businessStates();
        $this->industries = (new Industries)->getMainIndustries();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $forms = $this->forms->where('is_default', 1)->orderBy('name', 'asc')->get();

        foreach ($forms as $key => $form) {
            foreach ($this->categories as $category) {
                if ($category->id == $form->category_id) {
                    $forms[$key]->category_name = $category->name;
                }
            }
        }

        return view('admin.forms.list')->with('forms', $forms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $categories = Category::where('grouping', 'forms')->orderBy('order', 'asc')->get();

        return view('admin.forms.create')
                    ->with('categories', $categories)
                    ->with('states', $this->states)
                    ->with('industries', $this->industries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['is_default'] = 1;
        $data['business_id'] = 0;
        $data['status'] = 'enabled';
        $this->storeUploadedFile($request);
        $data['file_name'] = $request->file($this->forms->getUploadFieldName())->getClientOriginalName();
        
        $this->forms->create($data);

        return redirect('/admin/forms');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $form = $this->forms->find($id);
        $form->state = $form->state;
        $categories = Category::where('grouping', 'forms')->orderBy('order', 'asc')->get();

        return view('admin.forms.edit')->with('categories', $categories)
                                       ->with('form', $form)
                                       ->with('states', $this->states)
                                       ->with('industries', $this->industries);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
      
        $forceFormsReset = false;
        $form = $this->forms->findOrFail($id);

        $form->name = $request->input('name');
        $form->type = $request->input('type');
        $form->category_id = $request->input('category_id');
        $form->state = $request->input('state');
        $form->industries = $request->input('industries');
        $form->min_employee = $request->input('min_employee');
        $form->max_employee = $request->input('max_employee');
        

        if ($request->file('file_upload')) {
            $this->storeUploadedFile($request, $form->file_name);
            $filename = $request->file($this->forms->getUploadFieldName())->getClientOriginalName();

            // if a new form file is uploaded but the name is the same, flag the
            // business forms reset.
            if ($filename == $form->file_name) {
                $forceFormsReset = true;
            }

            $form->file_name = $filename;
        }

        // Save form template and update all business forms if data provided is
        // different or if a new file (with the same name) gets uploaded.
        if ($form->isDirty() || $forceFormsReset === true) {
            Form::where('template_id', $id)->update(['file' => $form->file_name, 'edited' => 'no']);
            $form->save();
        }

        return redirect('/admin/forms');
    }

    public function changeStatus(Request $request, $id)
    {
        $form = FormTemplate::findOrFail($id);
        if ($form->status === 'active' or $form->status === 'enabled') {
            $form->status = 'disabled';
        } elseif ($form->status === 'disabled') {
            $form->status = 'enabled';
        }

        $form->save();

        Form::where('template_id', '=', $form->id)->update(['status' => $form->status]);

        return redirect('/admin/forms');
    }

    /**
     * @param $id
     * @return Response
     */
    public function preview($id)
    {
        $errors = new MessageBag();

        $form = FormTemplate::findOrFail($id);

        $filename = $form->file_name;

        $headers = [];

        if (strpos($filename, '.pdf', -4)) {
            $headers = [
                'Content-Disposition'   => 'inline; filename="'.$filename.'"',
                'Content-Type'          => 'application/pdf',
            ];
        }

        if (strpos($filename, '.doc', -4)) {
            $headers = [
                'Content-Disposition'   => 'attachment; filename="'.$filename.'"',
                'Content-Type'          => 'application/msword',
            ];
        }

        if (strpos($filename, '.docx', -5)) {
            $headers = [
                'Content-Disposition'   => 'attachment; filename="'.$filename.'"',
                'Content-Type'          => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];
        }

        if (empty($headers)) {
            $errors->add('error', 'This format type is not available for download or view.  Only .pdf/.doc/.docx supported');

            return redirect()->back()->withErrors($errors);
        } else {
            $filepath = storage_path();
            $filepath .= DIRECTORY_SEPARATOR.$form->getUploadPath();
            $filepath .= DIRECTORY_SEPARATOR.$filename;

            return response()->make(file_get_contents($filepath), 200, $headers);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $form = $this->forms->findOrFail($id);
        $form->delete();

        return redirect('/admin/forms');
    }

    /**
     * Storing PDF Forms in the file system. If current file is passed,
     * it will be removed.
     *
     * @param Request $request
     * @param null $current_file
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    private function storeUploadedFile(Request $request, $current_file = null)
    {
        $path = storage_path().DIRECTORY_SEPARATOR.$this->forms->getUploadPath();

        if ($current_file && file_exists($path.DIRECTORY_SEPARATOR.$current_file)) {
            unlink($path.DIRECTORY_SEPARATOR.$current_file);
        }

        $filename = $request->file($this->forms->getUploadFieldName())->getClientOriginalName();

        return $request->file($this->forms->getUploadFieldName())->move($path, $filename);
    }
}
