<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminAuth
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
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        //add Role Check
        if (! $this->auth->user()->hasRole('admin')) {
            //if($this->auth->user()->role != 'admin')
            return redirect('/');
        }

        Auth::guard('bonuspro')->logout();

        // If this isn't one of the $allowedActions in $this->isAdminAuthViewAsAction()
        // then forget the viewAs session data
        if(!$this->isAdminAuthViewAsAction()) {
            session()->forget(['viewAs', 'viewAsRole']);
        }

        return $next($request);
    }

    /**
     * $allowedActions is a list of controller actions that are restricted to Admins viewing as another User
     * @return bool
     */
    private function isAdminAuthViewAsAction()
    {

        $allowedActions = [
            'App\Http\Controllers\User\EmployeesController@deleteAttendanceRecord',
            'App\Http\Controllers\User\PoliciesController@compare',
        ];

        return in_array(Route::getCurrentRoute()->getActionName(), $allowedActions) ? true : false;

    }
}
