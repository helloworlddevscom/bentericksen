<?php namespace Bentericksen\ViewComposers;

use \Illuminate\Contracts\View\View;

class PolicyRejectModificationsViewComposer
{

    /*
     * Modal IDs
     */
    protected $new_modals = [
        'policyReject'
    ];


    /**
     * PolicyRejectModificationsViewComposer constructor.
     */
    public function __construct()
    {
    }


    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $modals = isset( $view->modals ) ? $view->modals : [];
        $modals = array_merge( $modals, $this->new_modals );
        $view->with('modals',  array_unique($modals));
    }
}