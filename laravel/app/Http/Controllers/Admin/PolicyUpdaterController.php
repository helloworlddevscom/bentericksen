<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\RunPolicyUpdates;
use App\Policy;
use App\PolicyTemplateUpdate;
use App\PolicyUpdater;
use Bentericksen\PolicyUpdater\PolicyUpdater as BentPolicyUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

/**
 * Class PolicyUpdaterController.
 *
 * Renders CRUD for the Policy Updaters (in the Admin Dashboard).
 *
 * Note: Policy Template CRUD is in a different file.
 * @see \App\Http\Controllers\Admin\PoliciesController
 */
class PolicyUpdaterController extends Controller
{
    /**
     * Displays the list view for PolicyUpdaters.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.policy.updates.index')->with('updates', PolicyUpdater::all());
    }

    /**
     * Displays the multi-page form for creating a PolicyUpdater and also handles saving the
     * data from each step to the database. (i.e., both GET and POST).
     *
     * @param Request|null $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $parameters = $request->all();
        $updater = new BentPolicyUpdater;
        $step = isset($parameters['step']) ? $parameters['step'] : null;

        if (! $step) {
            // creates a new Updater
            // FIXME: this saves a blank updater to the DB when you visit the step 1 page
            $updater = $updater->create();
            $step = 1;
        } else {
            // this saves data from the previous step
            $updater = $updater->load($request->except(['_token']));
        }

        // Saving is now complete. Prepare the variables to show in the template for the next step.

        $list = [];
        $other_list = [];
        $email_defaults = [];
        $sendInactive = false;

        if ($step == 2) {

            // determine the list of recipients.

            // this is automatically calculated based on the businesses and user
            // types, and is able to be changed by the admin before moving to step 3.

            // Determine if inactive clients should be included in email list automatically for update notification

            $inactiveYears = $parameters['inactive_clients'] ?? null;
            $sendInactive = $inactiveYears > 0;
            $emails = $updater->getEmailLists($inactiveYears);
            $list = $emails['list'];
            $other_list = $emails['other_list'];

        // done with creating the lists. they're now in $list or $other_lists
            // depending on the user's company and role.
        } elseif ($step == 3) {

            // Configure the email subject and body

            $messages = [
                'emails.required' => 'The email list cannot be empty. Please review your selection.',
            ];

            $validator = \Validator::make($request->all(), [
                'emails' => 'required',
            ], $messages);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $email_defaults['active_client_default'] = 'Placeholder Text';
            $email_defaults['inactive_client_default'] = 'Placeholder Text';
            $email_defaults['other_client_default'] = 'Placeholder Text';
        } elseif ($step == 4) {

            // The end of the form. This puts the PolicyTemplateUpdate into the
            // queue for processing. Processing and email sending are done
            // via artisan commands.

            $updater->finalize();
        }

        return view(
            'admin.policy.updates.'.$updater->view,
            [
                'updates' => $updater,
                'policies' => $updater->available_policies,
                'next' => $updater->next,
                'id' => $updater->id,
                'sendInactive' => $sendInactive,
                'list' => $list,
                'other_list' => $other_list,
                'email_defaults' => $email_defaults,
            ]
        );
    }

    /**
     * Detail page for a Policy Updater (available after it's fully completed.).
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $updater = new BentPolicyUpdater;
        $data = $updater->getPolicyUpdate($id);

        return count($data)
            ? view('admin.policy.updates.show')->with('updater', $data)
            : redirect('/admin/policies/updates');
    }

    /**
     * Delete a Policy Updater.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        PolicyUpdater::find($id)->delete();

        return back();
    }

    /**
     * Triggers a Policy Updater to run. This does the same thing as the 'userPolicyUpdates' and
     * 'policyUpdateEmail' console commands, this gets run in a job but using the web interface.
     *
     * This is for testing purposes only and can't be used in production.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function triggerPolicyUpdate($id)
    {
        $errors = new MessageBag();

        if (config('app.env') === 'production') {
            $errors->add('error', 'Manually triggering policy updates in production is prohibited!');

            return back()->withErrors($errors);
        }

        $policy_updates = PolicyUpdater::find($id);
        $policies = $policy_updates->policies;
        $policy_template_updates = json_decode($policies, true);

        if (is_null($policy_template_updates) || ! is_array($policy_template_updates)) {
            return back()->withErrors($errors);
        }

        $dt = new \DateTime('today');
        $today = $dt->format('Y-m-d H:i:s');
        // start_date is the send date
        $policy_updates->update(['start_date' => $today]);

        foreach ($policy_template_updates as $policy_templates_id => $policy_template_updates_id) {
            $policy_template_update = PolicyTemplateUpdate::find($policy_template_updates_id);
            $policy_template_update->update(['effective_date' => $today]);
            // as we are updating this, set all policies below this one a step back in the order to make sure no collisions happen.
            Policy::where('category_id', $policy_template_update->category_id)
                ->where('order', '>=', $policy_template_update->order)
                ->update([
                    'order' => DB::raw('`order` + 1'),
                ]);
            $errors->add('info', "Policy update triggered for: {$policy_template_update->admin_name} (id: {$policy_template_updates_id})");
        }

        RunPolicyUpdates::dispatch($id)
        ->onConnection('database')
        ->onQueue('policy-updates');

        return back()->withErrors($errors);
    }
}
