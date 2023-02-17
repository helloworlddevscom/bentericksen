<?php

namespace Bentericksen\ViewComposers;

use Illuminate\Contracts\View\View;

/**
 * Class BusinessEmployeeCountViewComposer
 *
 * Adds information to the View about needing to enter the number of employees for the Business
 *
 * @package Bentericksen\ViewComposers
 * @see \App\Http\Middleware\NumberOfEmployees
 */
class BusinessEmployeeCountViewComposer
{

    /*
     * Modal IDs
     */
    protected $new_modals = [
        'employeeCount',
    ];

    /**
     * Bind data to the global view object.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $modals = isset($view->modals) ? $view->modals : [];
        $modals = array_merge($modals, $this->new_modals);
        $view->with('modals', array_unique($modals))
            ->with('employee_count_warning', session()->get('employee_count_warning'));
    }
}
