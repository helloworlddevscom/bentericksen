<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\PolicyTemplate;
use App\PolicyTemplateUpdate;
use Bentericksen\Settings\States;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class PoliciesController.
 *
 * Renders pages for the Admin view of the Policy Template List.
 *
 * Business-specific Policy editing feature is in a different controller.
 * @see \App\Http\Controllers\User\PoliciesController
 */
class PoliciesController extends Controller
{

    /**
     * @var PolicyTemplate
     */
    protected $policyTemplate;

    /**
     * @var array
     */
    protected $states = [];

    /**
     * @var array
     */
    private $benefitType;

    /**
     * @var array
     */
    private $options;

    /**
     * @var Category
     */
    private $categories;

    public function __construct(PolicyTemplate $policies)
    {
        $this->policyTemplate = $policies;

        //remove this from the controller add to its own model or file
        $this->states = (new States)->businessStates();

        $this->benefitType = [
            'none' => 'None',
            'medical' => 'Medical',
            'dental' => 'Dental',
            'vision' => 'Vision',
            'vacation_pto' => 'PTO/Vacation',
            'sickleave' => 'Sick Leave',
        ];

        $this->options = [
            '' => '- Select One - ',
            'optional' => 'Optional',
            'required' => 'Required',
            'Dental' => ['doptional' => 'Optional', 'drequired' => 'Required'],
            'Commercial' => [
                'coptional' => 'Optional',
                'crequired' => 'Required',
            ],
            'Veterinarian' => [
                'voptional' => 'Optional',
                'vrequired' => 'Required',
            ],
            'Medical' => ['moptional' => 'Optional', 'mrequired' => 'Required'],
        ];
    }

    /**
     * Display a listing of Policy Templates.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.policy.index')
            ->with('policies', $this->policyTemplate->all()->sortBy('admin_name'))
            ->with('states', $this->states)
            ->with('categories', $this->getCategories()->pluck('name', 'id')->toArray());
    }

    /**
     * Show the form for creating a new Policy Template.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.policy.create')
            ->with('categories', $this->getCategories()->pluck('name', 'id')->toArray())
            ->with('states', $this->states)
            ->with('options', $this->options);
    }

    /**
     * Policy Templates have no detail page; redirect to edit page.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect("/admin/policies/{$id}/edit");
    }

    /**
     * Saves the new PolicyTemplate to the database, and creates a matching PolicyTemplateUpdate.
     * @return Response
     */
    public function store(Request $request)
    {
        //Add FormRequest for Validation
        $policyTemplate = new PolicyTemplate();
        $policyTemplate->category_id = $request->input('category_id');
        $policyTemplate->admin_name = $request->input('admin_name');
        $policyTemplate->manual_name = $request->input('manual_name');
        $policyTemplate->min_employee = ($request->input('employee_range_min') == '' ? null : $request->input('employee_range_min'));
        $policyTemplate->max_employee = ($request->input('employee_range_max') == '' ? null : $request->input('employee_range_max'));
        $policyTemplate->state = json_encode($request->input('benefit_state'));

        if ($request->input('requirement') == '') {
            $policyTemplate->requirement = null;
        } else {
            $policyTemplate->requirement = $request->input('requirement');
        }

        $date = ($request->input('policy_effective_date') == '' ? null : Carbon::createFromFormat('m/d/Y', $request->input('policy_effective_date')));
        $policyTemplate->effective_date = (is_null($date) ? $date : $date->toDateString().' 00:00:00');
        $policyTemplate->benefit_type = $request->input('benefit_type');
        $policyTemplate->content = str_replace('</al>', '</ol>', str_replace('<al>', '<ol class="arrow">', str_replace('<p>&nbsp;</p>', '', str_replace('<p></p>', '', $request->input('content')))));
        // per BENT-483, new policy templates should always be "enabled"
        $policyTemplate->status = 'enabled';
        $policyTemplate->include_in_benefits_summary = $request->input('include_in_benefits_summary');

        // put it at the end of its category
        $order = PolicyTemplate::where('category_id', $policyTemplate->category_id)->max('order');
        $policyTemplate->order = $order === null ? 0 : $order + 1;

        $policyTemplate->save();

        // we also have to create a PolicyTemplateUpdate, which is how the new policy becomes available to choose
        // to include in a PolicyUpdater
        $update = new PolicyTemplateUpdate();
        $update->category_id = $request->input('category_id');
        $update->admin_name = $request->input('admin_name');
        $update->manual_name = $request->input('manual_name');
        $update->min_employee = ($request->input('employee_range_min') == '' ? null : $request->input('employee_range_min'));
        $update->max_employee = ($request->input('employee_range_max') == '' ? null : $request->input('employee_range_max'));
        $update->state = json_encode($request->input('benefit_state'));

        if ($request->input('requirement') == '') {
            $update->requirement = null;
        } else {
            $update->requirement = $request->input('requirement');
        }
        $date = ($request->input('policy_effective_date') == '' ? null : Carbon::createFromFormat('m/d/Y', $request->input('policy_effective_date')));

        $update->effective_date = (is_null($date) ? $date : $date->toDateString().' 00:00:00');
        $update->benefit_type = $request->input('benefit_type');
        $update->content = str_replace('</al>', '</ol>', str_replace('<al>', '<ol class="arrow">', str_replace('<p>&nbsp;</p>', '', str_replace('<p></p>', '', $request->input('content')))));
        $update->status = 'enabled';
        $update->template_id = $policyTemplate->id;
        $update->save();

        return redirect('/admin/policies');
    }

    /**
     * Show the form for editing the Policy Template.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $policyTemplate = $this->policyTemplate->findOrFail($id);
        $policyTemplate->state = json_decode($policyTemplate->state);

        return view('admin.policy.edit')
            ->with('policy', $policyTemplate)
            ->with('categories', $this->getCategories()->pluck('name', 'id')->toArray())
            ->with('states', $this->states)
            ->with('options', $this->options)
            ->with('benefitTypes', $this->benefitType);
    }

    /**
     * Save changes to a Policy Template. Depending on the effective date of the change, this will either
     * update the template itself, or create a Policy Template Update object, which will cause the template to be
     * updated in the future.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //Add FormRequest for Validation
        $policyTemplate = $this->policyTemplate->findOrFail($id);

        $policyTemplate->category_id = $request->input('category_id');
        $policyTemplate->admin_name = $request->input('admin_name');
        $policyTemplate->manual_name = $request->input('manual_name');
        $policyTemplate->min_employee = ($request->input('employee_range_min') == '' ? null : $request->input('employee_range_min'));
        $policyTemplate->max_employee = ($request->input('employee_range_max') == '' ? null : $request->input('employee_range_max'));
        $policyTemplate->state = json_encode($request->input('benefit_state'));

        if ($request->input('requirement') == '') {
            $policyTemplate->requirement = null;
        } else {
            $policyTemplate->requirement = $request->input('requirement');
        }

        $date = ($request->input('policy_effective_date') == '' ? new Carbon : Carbon::createFromFormat('m/d/Y', $request->input('policy_effective_date')));
        $policyTemplate->effective_date = $date->toDateString();
        $policyTemplate->benefit_type = $request->input('benefit_type');
        $policyTemplate->include_in_benefits_summary = $request->input('include_in_benefits_summary');
        $policyTemplate->content = str_replace('</al>', '</ol>', str_replace('<al>', '<ol class="arrow">', str_replace('<p>&nbsp;</p>', '', str_replace('<p></p>', '', $request->input('content')))));

        $today = Carbon::now()->format('Y-m-d');
        $effective_date = new Carbon($request->input('policy_effective_date'));
        $effective_date = $effective_date->format('Y-m-d');

        // If the change is effective in the future, we don't update the PolicyTemplate row, but instead create a
        // PolicyTemplateUpdate object, which contains the info needed to update the PolicyTemplate later.
        // If the change is effective in the past, we directly update the PolicyTemplate.
        if ($effective_date > $today) {
            $policy_array = $policyTemplate->toArray();

            unset($policy_array['id']);

            $updated_policy = [];

            foreach ($policy_array as $key => $value) {
                $updated_policy[$key] = $value;
            }
            $updated_policy['template_id'] = $id;
            $policy_template_update = new PolicyTemplateUpdate($updated_policy);
            $policy_template_update->save();
        } else {
            $policyTemplate->save();
        }

        return redirect('/admin/policies');
    }

    /**
     * Changes the status of a Policy Template
     * (The "Enable"/"Disable" buttons on the list view go here).
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changeStatus($id)
    {
        $policyTemplate = PolicyTemplate::find($id);
        if ($policyTemplate->status == 'enabled') {
            $policyTemplate->status = 'disabled';
            
            $policy_template_update = $policyTemplate->toArray();

            $policy_template_update['template_id'] = $policy_template_update['id'];
            
            $ignoreFields = ['id', 'created_at', 'updated_at'];
            
            foreach($ignoreFields as $field) {
                unset($policy_template_update[$field]);    
            }
            
            $date = new Carbon;
            
            $date->add(3, 'month');

            $policy_template_update['effective_date'] = $date->toDateString();

            $policy_template_update = new PolicyTemplateUpdate($policy_template_update);

            $policy_template_update->save();
        } else {
            $policyTemplate->status = 'enabled';
        }

        $policyTemplate->save();

        return redirect('/admin/policies');
    }

    /**
     * Displays the form to sort the categories.
     *
     * (This sorts the categories themselves, not the policies within them)
     *
     * Sorting the policies within the categories is handled elsewhere
     * @see PoliciesController::categorySorts()
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sorts()
    {
        $categories = Category::where('business_id', 0)
            ->where('grouping', 'policies')
            ->orderBy('order', 'asc')
            ->get();

        return view('admin.policy.sort', compact(['categories']));
    }

    /**
     * Saves sort order of categories.
     * @see PoliciesController::sorts()
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sortUpdate(Request $request)
    {
        foreach ($request->category as $key => $category) {
            Category::where('id', $key)
                ->where('business_id', 0)
                ->update(['order' => $category]);
        }

        return redirect('/admin/policies/sort');
    }

    /**
     * Displays the sort form to sort the policy templates within a category.
     *
     * Not to be confused with sorts() which sorts the categories themselves
     * @see PoliciesController::sorts()
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categorySorts($id)
    {
        $category = Category::where('id', $id)->first();
        $templates = PolicyTemplate::where('category_id', $id)
            ->orderBy('order', 'asc')
            ->get();

        return view('admin.policy.sortTemplates', compact([
            'category',
            'templates',
        ]));
    }

    /**
     * Saves sort order of policies within a category.
     * @see PoliciesController::categorySorts()
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function categorySortUpdate(Request $request)
    {
        if (! is_null($request->policyTemplate)) {
            foreach ($request->policyTemplate as $key => $template) {
                PolicyTemplate::where('id', $key)
                    ->update(['order' => $template]);
                $policyTemplateUpdate = PolicyTemplateUpdate::where('template_id', $key)->first();
                if (! empty($policyTemplateUpdate)) {
                    $policyTemplateUpdate->update(['order' => $template]);
                }
            }
        }

        return redirect('/admin/policies/sort');
    }

    /**
     * Helper method to get the available Policy Categories (with caching).
     *
     * @return \App\Category|\App\Category[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getCategories()
    {
        if (! $this->categories) {
            $this->categories = Category::where('business_id', 0)
                ->where('grouping', 'policies')
                ->get();
        }

        return $this->categories;
    }
}
