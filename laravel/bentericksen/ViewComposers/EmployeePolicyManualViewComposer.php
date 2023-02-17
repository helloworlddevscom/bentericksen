<?php

namespace Bentericksen\ViewComposers;

use \Illuminate\Contracts\View\View;
use Auth;

class EmployeePolicyManualViewComposer
{

    private $manual_available;

    private $switch_status;


    /**
     * Create a new wrap composer.
     */
    public function __construct()
    {
        $this->manual_available = false;

        $viewUser = Auth::user();

        if ($viewUser->business->manual && $viewUser->business->manual !== '') {
            $this->manual_available = true;
        }

        $this->switch_status = false;

        if (isset($viewUser->business->finalized)) {
            $this->switch_status = $viewUser->business->finalized;
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
        if (!$this->manual_available) {
            $view->with('manual_available', $this->manual_available);
        }

            $view->with('switch_status', $this->switch_status);
        
    }
}
