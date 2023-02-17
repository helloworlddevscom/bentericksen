<?php

namespace Bentericksen\ViewComposers;

use App\User;
use \Illuminate\Contracts\View\View;
use Auth;
use DB;
use Session;

/**
 * Class PolicyUpdateViewComposer
 *
 * Adds some variables to the view if there are policies that the current business needs
 * to accept. This makes the red "You Have X New Updates" message appear below the nav bar.
 *
 * @package Bentericksen\ViewComposers
 * @see \App\Http\Middleware\PendingPolicyUpdates
 */
class PolicyUpdateViewComposer
{
    protected $viewUser;

    private $run;

    private $old_policies;

    private $policy_updates;

    private $user;

    /**
     * Create a new wrap composer.
     */
    public function __construct()
    {
        $this->user = Auth::user();

        if (session()->get('viewAs')) {
            $this->user = User::find(session()->get('viewAs'));
        }

        $this->run = false;

        // If business does not have access to HR Director,
        // don't run update process.
        if (!$this->user->business->hrdirector_enabled) {
            return $this;
        }

        // Note: The PendingPolicyUpdates middleware must run before this class, or some of the session
        // variables below won't be set, and the red "pending policy updates" banner won't appear correctly.

        $hasPendingPolicies = Session::get('policyUpdatesPending');

        if ($hasPendingPolicies === true) {
            $this->run = true;
            $this->old_policies = Session::get('oldPolicies');
            $this->policy_updates = Session::get('policyUpdates');

            Session::forget('oldPolicies');
            Session::forget('policyUpdates');

            if (is_null(Session::get('updateCurrent'))) {
                Session::put('updateCurrent', 1);
            }
        }
    }

    /**
     * Adds data about the policy updates to all views. This is used to display
     * the red banner when a policy update is pending.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if ($this->run) {
            $modals = isset($view->modals) ? $view->modals : [];
            $modals = array_merge($modals, [
                'policyUpdates',
                'policyUpdatesReminder',
                'policyCompare',
                'policyComplete',
                'cancelConfirm',
            ]);

            $view->with('modals', array_unique($modals))
                ->with('old_policies', $this->old_policies)
                ->with('policy_updates', $this->policy_updates)
                ->with('policy_updates_run', $this->run);
        } else {
            $modals = isset($view->modals) ? $view->modals : [];
            $modals[] = 'policyComplete';
            $modals[] = 'policiesPending';
            $view->with('policy_updates_run', $this->run)
                ->with('has_pending_policies', $this->user->business->hasPendingPolicies())
                ->with('modals', array_unique($modals));
        }
    }
}
