<?php

namespace Bentericksen\ViewComposers;

use App\User;
use Auth;
use Illuminate\Contracts\View\View;

class PolicyFinalizationViewComposer
{
    protected $user;

    /**
     * PolicyFinalizationViewComposer constructor.
     */
    public function __construct()
    {
        $this->user = Auth::user();

        if (session()->get('viewAs')) {
            $this->user = User::find(session()->get('viewAs'));
        }
    }


    /**
     * Bind data to the view
     *
     * @param \Illuminate\Contracts\View\View $view
     */
    public function compose(View $view)
    {
        $modals = isset($view->modals) ? $view->modals : array();

        $modals[] = 'policiesPending';

        $pending_policies = false;

        // If business policy has not been finalized, then add a modal to alert
        // user that printing policies are unavailable until finalized.
        if ($this->user->business->hasPendingPolicies()) {
            $pending_policies = true;
        }

        $view->with('modals', array_unique($modals))
            ->with('policies_pending', $pending_policies);
    }
}