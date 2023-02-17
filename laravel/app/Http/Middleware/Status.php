<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Status
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
     *
     * @return void
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
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $user = $this->auth->user();
            if ($user->status === 'disabled') {
                if ($user->hasRole('admin')) {
                    return redirect('/admin');
                } elseif ($user->hasRole('user')) {
                    return redirect('/user');
                } elseif ($user->hasRole('manager')) {
                    return redirect('/user');
                } elseif ($user->hasRole('employee')) {
                    return redirect('/employee');
                }
            }
        }

        return $next($request);
    }
}
