<?php

namespace App\Http\Middleware;

use App\Business;
use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Service\EmployeeCountReminder;
use Request;

/**
 * Class NumberOfEmployees.
 *
 * Calculates whether the Business has the number of employees set.
 *
 * @see \Bentericksen\ViewComposers\BusinessEmployeeCountViewComposer
 */
class NumberOfEmployees
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $user = session()->get('viewAs') ? User::find(session()->get('viewAs')) : $this->auth->user();
            $business = Business::find($user->business_id);

            if (!$this->auth->user()->hasRole('admin') && $business->additional_employees == 0) {
                $request->session()->flash('employee_count_warning',
                    [
                        'show_warning' => true,
                        'show_reminder' => false
                    ]);
                return $next($request);
            }

            if (EmployeeCountReminder::displayEmployeeReminder($business)
                && $user->hasRole(['admin', 'owner', 'consultant', 'manager'])) {
                $request->session()->flash('employee_count_warning', [
                    'show_warning' => false,
                    'show_reminder' => true
                ]);
            }
        }
        return $next($request);
    }
}
