<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Form;
use App\FormTemplate;
use App\FormTemplateRule;

use App\Http\Controllers\Controller;
use App\User;
use App\Category;

use Bentericksen\Forms\FormsPopulator;
use Bentericksen\PrintServices\FormPrintService;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class FormsController extends Controller
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Business
     */
    protected $business;

    protected $forms;

    protected $categories;

    protected $templates;

    protected $category_order;

    /**
     * @var FormTemplate
     */
    protected $formTemplate;

    public function __construct(Request $request, ViewAs $viewAs, FormTemplate $formTemplate)
    {
        $this->user = User::find($viewAs->getUserId());
        $this->business = $this->user->business;
        $this->formTemplate = $formTemplate;
        $this->category_order = Category::where('grouping', 'forms')->get()->reduce(function($out, $category) {
          $out[$category->name] = $category->order;
          return $out;
        }, []);

        $businessForms = $this->user->business->forms()->pluck('template_id')->sort()->toArray();

        // this checks the existing templates, and creates a form for the
        // current business based on each template, if the business doesn't
        // already have one.
        $rules = FormTemplateRule::all();
        $this->templates = (new FormsPopulator($this->business, FormTemplate::all(), $rules))->forms();
        
        if ($businessForms !== $this->templates->pluck('id')->sort()->toArray()) {
          $this->initializeForms(true);
        }
        
        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $business = $this->user->business()->with([
            'forms' => function ($q) {
                $q->where('status', '=', 'enabled');
            },
            'forms.template',
            'forms.template.category',
        ])->first();
        
        return view('user.forms.index')
            ->with('business', $business)
            ->with('order', $this->category_order);
    }

    /**
     * Resetting Forms (per business).
     * @return mixed
     */
    public function reset()
    {        
        $this->initializeForms(true);

        return redirect('/user/forms')->withSuccess('Forms were reset successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['is_default'] = 0;
        $data['business_id'] = $this->user->business->id;
        $data['status'] = 'active';
        $this->forms->create($data);

        return redirect('/user/forms');
    }

    /**
     * Show the form for editing a form.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);
        $this->checkPermissions($form);

        return view('user.forms.edit')->with('form', $form)->with('categories', $this->categories);
    }

    /**
     * Saves a form to the DB. The "edit" form posts here.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        $this->checkPermissions($form);

        $form->edited = 'yes';
        $form->save();

        return redirect('/user/forms');
    }

    /**
     * Restore the form.
     *
     * @param $id
     * @return Response
     */
    public function restore($id)
    {
        $form = Form::findOrFail($id);
        $this->checkPermissions($form);
        $formTemplate = FormTemplate::findOrFail($form->template_id);

        $form->description = $formTemplate->description;
        $form->edited = 'no';
        $form->save();

        return redirect('/user/forms');
    }

    public function preview($id)
    {
        dd('report if seen');
    }

    /**
     * Generates a PDF of the form.
     * @param $id Form ID
     * @return \Illuminate\Http\Response
     */
    public function printForm($id)
    {
        $errors = new MessageBag();

        $form = Form::findOrFail($id);
        $this->checkPermissions($form);

        $filename = $form->file;

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

        // This is non-ideal, but for non-supported file types, the "print" option on users/forms will always
        // open a new browser if successful return, so redirect is not an option here.
        // Keeping the same error message as would have been seen before, but long-term solution would be to refactor
        // how the /users/forms handles printing forms... but there is an expected usage model already, so not breaking
        // the existing user experience... only to support an error case.
        //
        // See /admin/forms/edit.blade.php for example of redirecting with errors example.
        if (empty($headers)) {
            $headers = [
                'Content-Disposition'   => 'inline; filename="'.$filename.'"',
                'Content-Type'          => 'application/pdf',
            ];
        }
        $path = $form->folder.$filename;

        return response()->make(file_get_contents(storage_path($path)), 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function eSignatureOnlineSubmissions()
    {
        return view('user.forms.index');
    }

    /**
     * Duplicate the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function duplicate($id)
    {
        $form = $this->forms->findOrFail($id);
        $this->checkPermissions($form);
        $form = $form->replicate();
        $form->business_id = $this->user->business->id;
        $form->is_default = 0;
        $form->save();

        return redirect('/user/forms');
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
        $this->checkPermissions($form);
        $form->delete();

        return redirect('/user/forms');
    }

    /**
     * Helper method - checks whether the current logged in user can see or edit
     * the form. Throws a 403 if not authorized.
     * @param \App\Form $form
     */
    private function checkPermissions($form)
    {
        // check that the loaded employee is in the current business
        // (for admins/consultants using the "view as" feature, this check will
        // only pass if the employee belongs to the *currently viewed* business.
        if ($form->business_id !== $this->user->business->id) {
            abort(403, 'You do not have permission to see this content.');
        }
    }

    /**
     * Initializing Business' forms.
     *
     * @param bool $deleteForms
     *
     * @return $this
     */
    protected function initializeForms($deleteForms = false)
    {
        if ($deleteForms) {
            $this->deleteForms();
        }

        $templates = $this->templates;

        $forms = [];

        $user = $this->user;
        $business = $this->business;

        foreach ($templates as $temp) {
            $data = [
                'template_id'   => $temp->id,
                'description'   => $temp->description,
                'status'        => $temp->status,
                'edited'        => 'no',
                'folder'        => $temp->getUploadPath().DIRECTORY_SEPARATOR,
                'file'          => $temp->file_name ? $temp->file_name : 'temp',
            ];

            array_push($forms, new Form($data));

            if ($data['file'] == 'temp') {
                $filename = Str::random(40);
                $formService = new FormPrintService($user->business, $user->business->forms, $filename);
                $formService->generate();
            }
        }

        $business->forms()->saveMany($forms);
        $business->save();

        $this->business = $business->fresh();
        $this->user = $user->fresh();

        return $this;
    }

    /**
     * Deleting current Business' forms.
     *
     * @return $this
     */
    protected function deleteForms()
    {
        Form::where('business_id', $this->business->id)->delete();

        return $this;
    }
}
