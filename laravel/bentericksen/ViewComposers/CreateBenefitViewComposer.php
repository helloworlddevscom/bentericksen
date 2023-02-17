<?php

namespace Bentericksen\ViewComposers;

use \Illuminate\Contracts\View\View;
use Auth;
use User;


class CreateBenefitViewComposer
{
    /**
     * @var Auth
     */
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
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $modals = isset($view->modals) ? $view->modals : [];

        /**
         * Include Modal if needed - manual set to null
         */
        if ( is_null($this->user->business->manual) ) {
            $benefitCreateState = true;
            $manual_modals = ['benefitsSummaryCreate'];
            $modals = array_merge($modals, $manual_modals);
        } else {
            $benefitCreateState = false;
        }

        $view->with('modals', array_unique($modals))
            ->with('benefit_create_warning', $benefitCreateState);
    }
}
