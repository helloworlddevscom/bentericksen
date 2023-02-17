<?php

namespace Bentericksen\ViewComposers;

use \Illuminate\Contracts\View\View;
use Auth;
use Session;
use User;


class CreateManualViewComposer
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
        $manual_modals = ['createManual', 'policiesReset'];

        if (!$this->user->business->manual && Session::get('manualRegenerate')) {
            $manual_modals[] = 'manualOutOfDate';
            $view->with('manual_regenerate', true);
        }

        $modals = array_merge($modals, $manual_modals);


        $view->with('modals', array_unique($modals));
    }
}
