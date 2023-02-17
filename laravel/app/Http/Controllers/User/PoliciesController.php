<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Http\Controllers\Controller;
use App\Mail\PendingPolicyReview;
use App\Mail\PolicyModificationEmail;
use App\Mail\PolicyReviewSubmitted;
use App\OutgoingEmail;
use App\Policy;
use App\PolicyTemplate;
use App\PolicyTemplateUpdate;
use App\User;
use Bentericksen\Policy\HandleSpecials;
use Bentericksen\Policy\PolicySpecialRules;
use Bentericksen\PolicyUpdater\UserPolicyUpdate;
use Bentericksen\PrintServices\BenefitSummaryPrintService;
use Bentericksen\PrintServices\ManualPrintService;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Storage;
use Caxy\HtmlDiff\HtmlDiff;
use Caxy\HtmlDiff\HtmlDiffConfig;
use App\Jobs\UpdatePolicies;
/**
 * Class PoliciesController.
 *
 * Renders pages for the Business view of the Policy List.
 *
 * Admin Policy Template editing feature is in a different controller.
 * @see \App\Http\Controllers\Admin\PoliciesController
 */
class PoliciesController extends Controller
{
    /**
     * @var \App\User
     */
    private $user;

    /**
     * @var Business
     */
    private $business;
    private $categories;
    private $permissions;
    private $viewAs;

    const DIFF_STORAGE_PATH = 'bentericksen/diff';

    public function __construct(ViewAs $viewAs)
    {
        $this->viewAs = $viewAs;
        $this->user = \User::findOrFail($viewAs->getUserId());

        if (is_null($this->user->business_id)) {
            redirect('/')->send();
        }

        $this->business = Business::findOrFail($this->user->business_id);

        $this->categories = DB::table('categories')
            ->whereIn('business_id', [0, $this->business->id])
            ->where('grouping', 'policies')
            ->get();
    }

    /**
     * Display a listing of the Policies for the current Business.
     *
     * @return Response
     */
    public function index()
    {
        $policies = $this->business->getSortedPolicies(false);

        $categories = [];
        foreach ($this->categories as $cat) {
            $categories[$cat->id] = $cat;
        }

        foreach ($policies as $key => $policy) {
            if (isset($categories[$policy->category_id])) {
                $policies[$key]->category_name = $categories[$policy->category_id]->name;
                $policies[$key]->category_sort = $categories[$policy->category_id]->order;
            } else {
                $policies[$key]->category_name = 'Uncategorized';
                $policies[$key]->category_sort = 999;
            }
        }

        $business = $this->business;

        return view('user.policies.list', compact(['policies', 'business']));
    }

    /**
     * Show the form for creating a new policy.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.policies.create',
            [
                'categories' => $this->categories,
                'displayRequiredSelect' => $this->viewAs->getActualUser()->hasRole('admin')
            ]
        );
    }

    /**
     * Save a policy to the DB. This is where the "create" form posts.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $isTrackingActive = filter_var($request->input('is_tracking'), FILTER_VALIDATE_BOOLEAN);

        $validation = $request->validate(
            [
                'manual_name'       => 'required',
                'category_id'         => 'required',
                'required'            => $this->viewAs->getActualUser()->hasRole('admin') ? 'required' : 'optional'
            ],
            [
                'category_id.required' => 'The category field is required.',
                'required.required' => 'The required field is required'
            ]
        );

        $policy = new Policy();
        $policy->business_id = $this->business->id;
        $policy->category_id = $request->request->get('category_id');
        $policy->manual_name = $request->request->get('manual_name');
        $policy->content = $request->request->get('content_raw');
        $policy->content_raw = $request->request->get('content_raw');
        $policy->template_id = $request->request->get('template_id', 0);
        $policy->is_modified = 1;
        $policy->is_custom = true;
        if ($policy->category_id == 4) {
            $policy->include_in_benefits_summary = 1;
        }
        $policy->requirement = $this->viewAs->getActualUser()->hasRole('admin') ? $request->request->get('required') : 'optional';

        // If tracking is activated, mark the policy as pending so it can go through
        // the approval process.
        // Possible values: enabled or pending. Enabled will allow for manual creation
        $policy->status = $isTrackingActive ? 'pending' : 'enabled';

        // If tracking is activated, mark the policy as "pink".
        // Possible values: yes or no. Yes will mark the record as "pink"
        $policy->edited = $isTrackingActive ? 'yes' : 'no';

        $policy->save();

        // notifications should be sent if the current user is not an admin or consultant
        // or is a consultant and the business is finalized.
        if (! in_array(Auth::user()->getRole(), ['consultant', 'admin']) ||
            ($this->business->finalized && Auth::user()->getRole() !== 'admin')) {
            $this->sendNotifications($policy);
        }

        return redirect('/user/policies');
    }

    /**
     * Show the form for editing the current Business' copy of the policy.
     * (For updating global Policy Templates, @see \App\Http\Controllers\Admin\PoliciesController::edit).
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $policy = Policy::find($id);
        $this->checkPermissions($policy);

        $isPolicy = ! is_null($policy->template) && ! is_null($policy->template->benefit_type) ? true : false;

        $statusButtonClasses = [
            'btn',
            'btn-default',
            'btn-xs',
            'form-btn',
        ];

        $statusButtonClasses[] = $policy->status == 'enabled' ? 'btn-danger' : 'btn-primary';
        $statusButtonLabel = $policy->status == 'enabled' ? 'DISABLE' : 'ENABLE';

        return view('user.policies.edit', [
            'policy' => $policy,
            'categories' => $this->categories,
            'isPolicy' => $isPolicy,
            'displayApprovalButtons' => $this->displayApprovalButtons($policy),
            'statusButton' => [
                'class' => implode(' ', $statusButtonClasses),
                'label' => $statusButtonLabel,
            ],
            'displayRequiredSelect' => $this->viewAs->getActualUser()->hasRole('admin')
        ]);
    }

    /**
     * Saves the data from the "Edit" form to the DB.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        if($this->viewAs->getActualUser()->hasRole('admin')) {
            $validation = $request->validate(
                ['required' => 'required'],
                ['required.required' => 'The required field is required']
            );
        }

        // if modification is being rejected/accepted, there are new fields coming from modal window.
        $modification_approval_result = $request->input('modification_approval');
        // checks if CKEditor is passing edited content
        $mods = (int) $request->input('mod_counter');
        // checks for tracked changes
        // Logical result can be either 0/1 or true/false.   Needs refactor!
        $tracking_changes = filter_var($request->input('is_tracking'), FILTER_VALIDATE_BOOLEAN);

        $values = [
            'manual_name' => $request->request->get('manual_name'),
            'requirement' => $this->viewAs->getActualUser()->hasRole('admin') ? $request->request->get('required') : 'optional',
            'content_raw' => str_replace('</al>', '</ol>', str_replace('<al>', "<ol class='arrow'>", $request->request->get('content_raw'))),
            'content' => str_replace('</al>', '</ol>', str_replace('<al>', "<ol class='arrow'>", $request->request->get('content_raw'))),
            'category_id' => ($request->request->get('category_id') == '' ? 0 : $request->request->get('category_id')),
            'updated_at' => (new Carbon)->format('Y-m-d h:i:s'),
            'new_page' => $request->request->get('new_page') ? 1 : 0,
        ];

        // Checking if content was in fact modified. If not, redirect to list page. - this was catching during "Accept/Reject".
//        if (!$this->wasPolicyModified($values, $policy) && empty($modification_approval_result)) {
//            return redirect('/user/policies#policy_' . $id);
//        }

        $policy = Policy::find($id);
        $this->checkPermissions($policy);


        // If changes made, change to pending and edit.
        // status decoder:
        // pending = remove enable/disable button --> makes "edit" and no longer allows enable/disable state.
        //           If anything is in pending state, create policy manual button is disabled until approved/rejected
        // edited  = Changes to purple color.
        // NOTE:  if you open and do not make edits, you will still see tracking changes, but mod's = 0.   So don't capture case when
        // user just opens and resaves without any edits
        if (($tracking_changes) && ($mods > 0)) {
            $values['status'] = 'pending';
            $values['edited'] = 'yes';
        }

        // This is the approved/rejected case.   Return to enabled and not edited when completed
        // When $modification_approval_result isn't empty, a decision has been made on the edit.  Return to enabled, no edit.
        // OR
        // If edits are made and saved, always toggle plan to enable state.
        // For Owner, Manager, and consultant.   Tracking changes are enabled always.
        // When $modification_approval_result IS empty, a decision hasn't been made on the edit.  This is the case when a user
        // makes an edit, but the plan is disabled.  This logic will force the state to enabled if modifications exist.

        if (!$tracking_changes) {
            if (!empty($modification_approval_result) || (empty($modification_approval_result) && empty($mods))) {
                $values['status'] = 'enabled';
                $values['edited'] = 'no';
            }
        }

        // If previously modified, but now no modification remain and no approvals necessary
        // else, use mods count to determine if modified or not.
        if ($policy->is_modified == 1 && $mods == 0 && empty($modification_approval_result)) {
            $values['is_modified'] = 1;
        } else {
            $values['is_modified'] = $mods > 0 ? 1 : 0;
        }

        // eliminating warning highlighting for rejected modifications
        if ($mods > 0) {
            $values['content'] = str_replace('ice-cts-3', 'ice-cts-1', $values['content']);
            $values['content_raw'] = str_replace('ice-cts-3', 'ice-cts-1', $values['content_raw']);
        }

        // Ensure that non-admin users can't edit the manual_name by bypassing the 'readonly' form element
        if (! $policy->userCanEdit($this->viewAs->getActualUser())) {
            if ($values['manual_name'] !== $policy->manual_name) {
                $values['manual_name'] = $policy->manual_name;
            }
        }

        $policy->update($values);

        // notifications should be sent if the current user is not an admin or consultant
        // or is a consultant and the business is finalized.
        if ($mods > 0) {
            if (! in_array(Auth::user()->getRole(), ['consultant', 'admin']) || ($this->business->finalized && Auth::user()->getRole() !== 'admin')) {
                $this->sendNotifications($policy);
            }
        }

        if ($modification_approval_result) {
            $this->sendApprovalNotifications($policy, $modification_approval_result);
        }

        return redirect('/user/policies#policy_'.$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function acceptUpdate(Request $request, $id)
    {
        $templateUpdate = PolicyTemplateUpdate::find($id);

        if (empty($templateUpdate)) {
            return;
        }

        $content = html_entity_decode($request->input('content'));
        $content_raw = html_entity_decode($request->input('content_raw'));
        $modal_accept = $request->input('modal_accept');
        $update_was_modified = $request->input('modified') == '1';

        $values = [
            'content_raw' => str_replace('</al>', '</ol>', str_replace('<al>', "<ol class='arrow'>", $request->request->get('content'))),
            'content' => str_replace('</al>', '</ol>', str_replace('<al>', "<ol class='arrow'>", $request->request->get('content'))),
            'manual_name' => $request->request->get('manual_name'),
            'updated_at' => (new Carbon)->format('Y-m-d h:i:s'),
            'edited' => 'no',
        ];

        if (! empty($request->input('enableInput'))) {
            $values['status'] = (int) $request->input('enableInput') == 1 ? 'enabled' : 'disabled';
        }

        if ($update_was_modified && $modal_accept) {
            $values['is_modified'] = 0;
            $values['edited'] = 'no';
            $values['status'] = 'enabled';
        }

        $policy = Policy::where('business_id', $this->business->id)
            ->where('template_id', $templateUpdate->template_id)
            ->latest()
            ->first();

        $values['requirement'] = current($templateUpdate->requirement);

        if (! is_null($policy)) {
            $selectedPolicy = $policy->special != 'stub' ? null : Policy::where('special_extra', $policy->id)->first();

            if (! is_null($selectedPolicy) && $selectedPolicy->template_id == $templateUpdate->template_id) {
                $selectedPolicy->update($values);
            }

            if (! is_null($selectedPolicy)) {
                unset($values['status']);
            }

            $policy->update($values);

        } else {
            $template = PolicyTemplate::find($templateUpdate->template_id);
            $values['business_id'] = $this->business->id;
            $values['category_id'] = $template->category_id;
            $values['template_id'] = $templateUpdate->template_id;
            $values['manual_name'] = $template->manual_name;
            $values['status'] = $template->status;
            $values['order'] = $template->order;
            $values['requirement'] = $template->requirement[0];
            $values['tags'] = $template->tags;
            $values['edited'] = $update_was_modified ? 'yes' : 'no';

            $policy = Policy::create($values);
        }

        // Generating an array with all Business' users. Accepting the update will
        // flag all of them as accepted.
        $users = User::where('business_id', $this->business->id)->get()->pluck('id')->toArray();

        // Checking if user is logged in (or viewing as) is on the list of business' users.
        // This accounts for consultants accepting the updates for given business.
        $user_id_accepting_update = $this->viewAs ? Auth::user()->id : $this->user->id;
        if (! in_array($user_id_accepting_update, $users)) {
            array_push($users, $user_id_accepting_update);
        }

        // Checking for business consultant.
        $consultant = $this->business->getConsultant();

        // If one exists...
        if ($consultant) {
            // We'll add a consultant to business' users list only if business is NOT finalized
            // or if ongoing_consultant_cc flag is set on a finalized business
            if ($this->business->finalized == 0 || $this->business->ongoing_consultant_cc == 1) {
                if (! in_array($consultant->id, $users)) {
                    array_push($users, $consultant->id);
                }
            }
        }

        $viewUser = session('viewAs') ? User::find(session('viewAs')) : \Auth::user();

        $updates = new UserPolicyUpdate($this->user);
        $updates->setUsers($users);
        $updates->acceptUpdates($id);

        if (count($updates->setUpdatedPolicies())) {
            // there are more items, so show the next one on next page load
            \Session::put('accepted', true);
        } else {
            // all updates accepted; show the policyComplete modal
            \Session::flash('accepted', 'complete');
        }

        if (\Session::get('updateCurrent') == \Session::get('updateTotal')) {
            // Ensure that policies are up to date with the business rules.
            // This will remove any policies that no longer apply to the business
            // due to state or employee count changes.
            UpdatePolicies::dispatch($this->business->id);

            // if current update is equals total number of updates, reset counters,
            // remove pending updates, and remove flag.
            \Session::forget('updateCurrent');
            \Session::forget('updateTotal');
            \Session::put('policyUpdatesPending', false);

            // Delete current manual from business so that it needs to be
            // regenerated.
            \Session::put('manualRegenerate', true);
            $this->business->manual = null;
            $this->business->save();
        } else {
            // if current update is less than the total of updates,
            // increment counter and keep updating.
            \Session::put('updateCurrent', \Session::get('updateCurrent') + 1);
        }

        return redirect('/user');
    }

    public function restore(Request $request, $id)
    {
        $policy = Policy::findOrFail($id);
        $this->checkPermissions($policy);

        $template = DB::table('policy_templates')
            ->where('id', $policy->template_id)
            ->first();

        $values = [
            'manual_name'   => $policy->manual_name,
            'content_raw'   => $template->content,
            'content'       => $template->content,
            'updated_at'    => '0000-00-00 00:00:00',
            'edited'        => 'no',
            'is_modified'   => 0,
            'status'        => $template->status,
        ];

        $policy = DB::table('policies')
            ->where('id', $id)
            ->where('business_id', $this->business->id)
            ->update($values);

        return redirect('/user/policies#policy_'.$id);
    }

    /**
     * Enables or disables a Policy.
     *
     * @param Request $request
     * @param $id
     * @param PolicySpecialRules $policySpecialRules
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed|string
     */
    public function toggleStatus(Request $request, $id, PolicySpecialRules $policySpecialRules)
    {
        $policy = Policy::where('id', $id)
            ->where('business_id', $this->business->id)
            ->first();

        if (is_null($policy)) {
            return redirect('/user/policies');
        }

        if ($policy->status === 'closed') {
            return 'reload';
        }

        if ($policy->status === 'enabled') {
            $policy->status = 'disabled';
        } else {
            $policy->status = 'enabled';
        }

        $policy->save();

        $policySpecialRules->setBusiness($this->business);
        $special = $policySpecialRules->check($policy);

        if ($request->ajax()) {
            return $special === 'reload' ? $special : $policy->status;
        }

        return redirect('/user/policies');
    }

    public function addCategory(Request $request)
    {
        $category = [
            'name' => $request->input('categoryName'),
            'business_id' => $this->business->id,
        ];

        DB::table('categories')->insert($category);

        return redirect('/user/policies');
    }

    /**
     * Displays the form for sorting policies in a category.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sorting()
    {
        $categories = $this->categories;
        $policies = DB::table('policies')
            ->where('business_id', $this->business->id)
            ->where('status', '!=', 'closed')
            ->whereNull('delete_reason')
            ->orderBy('category_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();

        return view('user.policies.sort', compact(['categories', 'policies']));
    }

    /**
     * Saves the data for the "Policy sort" page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSorting(Request $request)
    {
        $policiesCollection = $request->input('policy');
        foreach ($policiesCollection as $key => $policies) {
            foreach ($policies as $ke => $policy) {
                $data = [
                    'order' => $policy,
                ];

                $pol = Policy::find($ke);
                $pol->update($data);
            }
        }

        return redirect('/user/policies');
    }

    /**
     * Restores the policy sort order to the default order based on the templates.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreSorting()
    {
        $policies = Policy::where('business_id', $this->business->id)
            ->where('status', '!=', 'closed')
            ->with('template')
            ->get();
        foreach ($policies as $policy) {
            if ($policy->template) {
                $policy->update(['order' => $policy->template->order]);
            }
        }

        return redirect('/user/policies')->with('success', 'Policies have been reset to the default sort order.');
    }

    public function downloadBenefitSummary(Request $request)
    {
        if (is_null($this->business->manual)) {
            return redirect()->back();
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '.$this->business->manual.'_summary.pdf',
        ];

        return response()
            ->make(
                file_get_contents(
                    storage_path('bentericksen/policy/'.$this->business->manual.'_summary')
                ), 200, $headers);
    }

    /**
     * Storing the manual token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createManualToken(Request $request)
    {
        $token = Str::random(10);

        $request->session()->put('manual_token', $token);

        return response()->json(['success' => true, 'token' => $token]);
    }

    /**
     * Validates manual token for the createManual()/reset() methods. Token is passed through JS
     * from the index view (user/policies/list.blade.php).
     *
     * @param Request $request
     * @return mixed
     */
    public function validateManualToken(Request $request)
    {
        $session_token = session('manual_token');
        $manual_token = $request->get('token');

        $validator = \Validator::make($request->all(), []);

        $validator->after(function ($validator) use ($manual_token, $session_token) {
            if (! $manual_token || ! session('manual_token') || $manual_token !== $session_token) {
                $validator->errors()->add('finalized', 'Invalid Token provided.');
            }
        });

        return $validator;
    }

    /**
     * Creates the manual and saves it to disk.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function createManual(Request $request)
    {
        $validator = $this->validateManualToken($request);

        // if token is invalid, redirect displaying error message on policies list page.
        if ($validator->fails()) {
            return redirect('/user/policies')
                ->withErrors($validator)
                ->withInput();
        }

        // removing manual_token from session.
        $request->session()->forget('manual_token');

        // Remove manual regeneration flag from session.
        $request->session()->forget('manual_regenerate');

        $this->generateManual();

        if (! is_null($request)) {
            $return_path = '/user/policies';

            if ($request->get('token')) {
                $return_path .= '/manual';
            }

            return redirect($return_path);
        }
    }

    public function downloadManual(Request $request)
    {
        if (is_null($this->business->manual)) {
            $stubs = Policy::where('business_id', $this->business->id)
                ->where('special', 'stub')
                ->where('status', 'enabled')
                ->get();

            if (count($stubs) > 0) {
                return view('user.manual_error')->with('stubs', $stubs);
            }

            $this->createManual($request);
        }

        $manualPath = storage_path('bentericksen/policy/'.$this->business->manual);

        if (! file_exists($manualPath)) {
            $manualPath = $this->generateManual();
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '.$this->business->manual.'.pdf',
        ];

        return response()->make(file_get_contents($manualPath), 200, $headers);

        /* If they want it to directly prompt download and not open in browser
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type'		  => 'application/pdf',
            //"Content-Disposition" => "attachment;filename=" . $this->business->manual . ".pdf",
            "Content-Disposition" => "attachment;filename=" . $this->business->manual . ".pdf",
            'Content-Transfer-Encoding' => 'binary',
            'Expires'			  => '0',
            'Cache-Control'		  => 'must-revalidate',
            'Pragma'			  => 'public',
            'Content-Length'	  => filesize( storage_path( 'bentericksen/policy/' . $this->business->manual) ),
        ];

        return response()->download(storage_path( 'bentericksen/policy/'. $this->business->manual), "PolicyManual.pdf", $headers);
        */
    }

    /**
     * Displaying policy selector page (i.e. grouped policies).
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function select($id)
    {
        $policy = Policy::findOrFail($id);
        $this->checkPermissions($policy);

        // if the stub is already closed (i.e. previously selected) or this isn't a
        // stub (selector type), return with an error message.
        if ($policy->special !== 'stub' || $policy->status == 'closed') {
            return redirect('/user/policies')->withErrors('Policy already selected.');
        }

        $specials = json_decode($policy->special_extra);
        $default = $specials->default ?? null;

        $policies = PolicyTemplate::whereIn('id', $specials->policies)->get();

        return view('user.policies.select')->with('policies', $policies)
            ->with('default', $default)
            ->with('stub', $policy);
    }

    /**
     * Selects the policy from a selector. See "SELECT" method.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function selectUpdate(Request $request, $id)
    {
        // returns if policy was not selected
        if (is_null($request->input('policySelect'))) {
            return redirect('/user/policies/'.$id.'/select');
        }

        // find the stub
        $stub = Policy::findOrFail($id);

        // if stub (selector) is closed, i.e. already selected,
        // return with an error message.
        if (empty($stub) || $stub->status == 'closed') {
            return redirect('/user/policies')->withErrors('Policy already selected.');
        }

        $template = PolicyTemplate::findOrFail($request->input('policySelect'));

        $data = [
            'business_id' => $this->business->id,
            'category_id' => $template->category_id,
            'template_id' => $template->id,
            'manual_name' => $template->manual_name,
            'content'     => $template->content,
            'content_raw' => $template->content,
            'status'      => 'enabled',
            'order'       => $stub->order,
            'requirement' => $template->getRequirement($this->business),
            'updated_at'  => '0000-00-00 00:00:00',
            'special'     => 'selected',
            'special_extra' => $id, // stub ID
            'include_in_benefits_summary' => $template->include_in_benefits_summary,
        ];

        $new = Policy::create($data);
        $stub->status = 'closed';
        $stub->save();

        return redirect('/user/policies/'.$new->id.'/edit');
    }

    /**
     * Resets policy selector.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function resetSelect($id)
    {
        $policy = Policy::findOrFail($id);
        $stub = null;

        if ($policy->special_extra && trim($policy->special_extra) !== '') {
            // if policy has assigned stub, open it.
            $stub = Policy::findOrFail($policy->special_extra);
        } else {
            // else, check all business stubs.
            $stubs = Policy::where('special', 'stub')
                ->where('status', 'closed')
                ->where('business_id', $policy->business_id)
                ->get();

            foreach ($stubs as $stubTemplate) {
                // decoding the special-extra property.
                $data = json_decode($stubTemplate->special_extra);

                // If currently policy is not in the stub, continue
                if (! in_array($policy->template_id, $data->policies)) {
                    continue;
                }

                // assign the current stub as the stub we need to activate.
                $stub = $stubTemplate;
                break;
            }
        }

        if ($stub) {
            // re-enabling stub
            $stub->update(['status' => 'enabled']);
            // removing current policy.
            $policy
                ->update([
                    'delete_reason' => 'Policy Select Reset'
                ]);
            $policy->delete();
        }

        return redirect('/user/policies/'.$stub->id.'/select');
    }

    /**
     * Resets business policies.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset(Request $request)
    {
        $validator = $this->validateManualToken($request);

        // if token is invalid, redirect displaying error message on policies list page.
        if ($validator->fails()) {
            return redirect('/user/policies')
                ->withErrors($validator)
                ->withInput();
        }

        // removing manual_token from session.
        $request->session()->forget('manual_token');

        if (\Auth::user()->hasRole('admin')) {
            UpdatePolicies::dispatch($this->business->id, true);

            return redirect('/user/policies')->with('success', 'All Policies Reset Queued. Please check back in a moment to see updated policies.');
        }

        return redirect('/user/policies');
    }

    /**
     * Generates manual.
     *
     * @return bool|string
     * @throws \Exception
     * @throws \Throwable
     */
    protected function generateManual()
    {
        $filename = Str::random(40);

        //create benefitSummary()
        $benefitSummaryPrintService = new BenefitSummaryPrintService($this->business, $filename);
        $benefitSummaryPrintService->generate();
        $manualService = new ManualPrintService($this->business, $filename);

        try {
            $manualService->generate();
            $this->business->manual = $filename;
            $this->business->manual_created_at = Carbon::now();
            $this->business->save();
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";

            return false;
        }

        return storage_path('bentericksen/policy/'.$this->business->manual);
    }

    /**
     * Verifies if any field of the policy was modified in the editor form.
     *
     * @param $values
     * @param $policy
     * @return bool
     */
    private function wasPolicyModified($values, $policy)
    {
        $verify_fields = ['content', 'category_id', 'manual_name', 'new_page'];

        $modified = false;

        foreach ($verify_fields as $field) {
            if ($policy->{$field} != $values[$field]) {
                $modified = true;
                break;
            }
        }

        return $modified;
    }

    /**
     * Sends notifications to consultant and business' contacts when
     * there are pending modifications on policy.
     *
     * @param $policy
     *
     * @todo Move this to be a method on the Policy class
     */
    private function sendNotifications($policy)
    {
        // TODO: $this->business should be replaced by the "business" relationship on the Policy model when moving this.
        // We're not actually using any other properties of $this except the business here, so this should be easy.
        $users_to_be_notified = $this->business->getBusinessUsers();
        $owners = $this->business->getNonPrimaryOwners();
        $managers = $this->business->getManagers();

        foreach($owners as $owner) {
            array_push($users_to_be_notified, $owner);
        }

        if (! empty($managers)) {
            foreach ($managers as $manager) {
                array_push($users_to_be_notified, [
                    'id' => $manager->id,
                    'full_name' => $manager->first_name.' '.$manager->last_name,
                    'email' => $manager->email,
                ]);
            }
        }

        $consultant = $this->business->getConsultant();

        // Notifying consultant or admins about policy change.
        // Checking if business is finalized, if yes notify the admins
        if ($this->business->finalized) {
            $mailable = new PendingPolicyReview(\Auth::user(), $policy);

            $mailer = new OutgoingEmail([], $mailable);
            $mailer->related_type = AppPolicy::class;
            $mailer->related_id = $policy->id;
            $mailer->to_address = 'hrsupport@bentericksen.com';
            $mailer->send();

            // if business is finalized, and consultant is flagged as ongoing, notify consultant (client email verbiage)
            if ($consultant && $this->business->notifyConsultant()) {
                array_push($users_to_be_notified, [
                    'id' => $consultant->id,
                    'full_name' => $consultant->full_name,
                    'email' => $consultant->email,
                ]);
            }
        } else {
            // if business has consultant assigned, notify consultant.
            if ($consultant) {
                $mailable = new PendingPolicyReview(\Auth::user(), $policy);
                $mailer = new OutgoingEmail([], $mailable);
                $mailer->user_id = $consultant->id;
                $mailer->related_type = AppPolicy::class;
                $mailer->related_id = $policy->id;
                $mailer->send();
            }
        }

        // Notifying clients.
        foreach ($users_to_be_notified as $user) {
            $mailable = new PolicyReviewSubmitted(\Auth::user(), $policy);
            $mailer = new OutgoingEmail([], $mailable);

            $user_account = User::where('email', $user['email'])->get(['terminated']);

            if (empty($user_account[0]->terminated)) {
                if (array_key_exists('id', $user)) {
                    $mailer->user_id = $user['id'];
                } else {
                    $mailer->to_address = $user['email'];
                }
                $mailer->related_type = AppPolicy::class;
                $mailer->related_id = $policy->id;
                $mailer->send();
            }
        }
    }

    /**
     * Notifies relevant users the result of the approval process.
     *
     * @param Policy $policy
     * @param array $modification_approval_result
     */
    private function sendApprovalNotifications($policy, $modification_approval_result)
    {
        $users_to_be_notified = $this->business->getBusinessUsers();
        $owners = $this->business->getNonPrimaryOwners();
        $managers = $this->business->getManagers();

        foreach($owners as $owner) {
            array_push($users_to_be_notified, $owner);
        }

        // Checking if assigned consultant should receive notifications.
        if ($this->business->notifyConsultant()) {
            $consultant = $this->business->getConsultant();

            // if business has consultant assigned, notify consultant.
            if ($consultant) {
                array_push($users_to_be_notified, ['full_name' => $consultant->full_name, 'email' => $consultant->email]);
            }
        }

        if (! empty($managers)) {
            foreach ($managers as $manager) {
                array_push($users_to_be_notified, [
                    'id' => $manager->id,
                    'full_name' => $manager->first_name.' '.$manager->last_name,
                    'email' => $manager->email,
                ]);
            }
        }

        foreach ($users_to_be_notified as $user) {
            $mailable = new PolicyModificationEmail(
                $policy,
                $modification_approval_result['decision'],
                $modification_approval_result['justification'] ?? ''
            );
            $mailer = new OutgoingEmail([], $mailable);

            $user_account = User::where('email', $user['email'])->get(['terminated']);

            if (empty($user_account[0]->terminated)) {
                if (array_key_exists('id', $user)) {
                    $mailer->user_id = $user['id'];
                } else {
                    $mailer->to_address = $user['email'];
                }
                $mailer->related_type = AppPolicy::class;
                $mailer->related_id = $policy->id;
                $mailer->send();
            }
        }
    }

    /**
     * Decision to whether or not display approval buttons on editor page.
     *
     * @param Policy $policy
     * @return bool
     */
    private function displayApprovalButtons($policy)
    {
        if (Auth::user()->getRole() !== 'admin') {
            return false;
        }

        // if policy is not flagged as modified, don't display buttons.
        if (! $policy->is_modified) {
            return false;
        }

        // if "finalized" attribute doesn't exist on Business entity.
        if (! isset($this->business->finalized)) {
            return true;
        }

        // if it does, only display approval buttons if business is finalized (post-switch)
        return $this->business->finalized;
    }

    /**
     * Helper method - checks whether the current logged in user can see or edit
     * the policy. Throws a 403 if not authorized.
     *
     * @param Policy $policy
     */
    private function checkPermissions($policy)
    {
        // check that the loaded employee is in the current business
        // (for admins/consultants using the "view as" feature, this check will
        // only pass if the employee belongs to the *currently viewed* business.
        if ($policy->business_id !== $this->business->id) {
            abort(403, 'You do not have permission to see this content.');
        }
    }

    public function destroy(Policy $policy)
    {
        if (Auth::user()->getRole() !== 'admin') {
            return false;
        }

        $policy
            ->update([
                'delete_reason' => 'Policy Manual Delete'
            ]);

        $policy->delete();

        return redirect('/user/policies');
    }

    public function compare(Policy $policy)
    {
        $diff = null;
        $error = '';
        $oldHtml = '';
        $newHtml = '';
        $noChanges = true;
        $storagePath = storage_path(self::DIFF_STORAGE_PATH);

        if (empty($policy->template)) {
            return redirect('user/policies');
        }

        try {
            $oldHtml = $policy->template->policyClean(null, true);
            $newHtml = $policy->policyClean(null, true);
        } catch(\Exception $e) {
            $oldHtml = $policy->template->content;
            $newHtml = $policy->content;

            error_log($e->getMessage());
            \Session::flash('error', $e->getMessage());
        }

        try {
            $diff = new HtmlDiff($oldHtml, $newHtml);
            $noChanges = preg_replace('/\s/', '', $oldHtml) == preg_replace('/\s/', '', $newHtml);
            $diff->getConfig()->setPurifierCacheLocation($storagePath);

            return view('user.policies.compare')
                ->with('default_content', $oldHtml)
                ->with('policy', $policy)
                ->with('noChanges', $noChanges)
                ->with('diff', $noChanges ? '' : $diff->build());
        } catch(\Exception $e) {
            error_log($e->getMessage());
            \Session::flash('error', $e->getMessage());
        }

        return view('user.policies.compare')
                ->with('default_content', $policy->template->content)
                ->with('policy', $policy)
                ->with('noChanges', true)
                ->with('diff', '');
    }
}
