<?php

namespace App\Http\Middleware;

use App\Role;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class BonusPro
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var auth
     */
    private $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth->user();
        $this->user = session()->get('viewAs') ? User::find(session()->get('viewAs')) : $auth->user();
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
        $role = $this->auth->getRole();

        if (in_array($role, [Role::ADMIN, Role::CONSULTANT])) {
            return $next($request);
        }

        $isLifetimeEnabled = $this->user->business->bonuspro_lifetime_access;
        $bonuspro_enabled = $this->user->business->bonuspro_enabled;
        $bonuspro_expiration = $this->user->business->bonuspro_expiration_date;

        if (! $bonuspro_enabled) {
            $msg = 'You don\'t have access to BonusPro. Please contact Customer Service.';
            session()->put('bonuspro_enabled', false);

            return redirect('/user')->withErrors([$msg]);
        }

        if (! $isLifetimeEnabled && $bonuspro_expiration < Carbon::now()) {
            $msg = 'Your BonusPro subscription is expired. Please contact Customer Service.';
            session()->put('bonuspro_enabled', false);

            return redirect('/user')->withErrors([$msg]);
        }

        if ($request->user()->hasRole('manager') && ! $request->user()->permissions('m101')) {
            return redirect()->route('user.dashboard');
        }

        session()->put('bonuspro_enabled', true);

        return $next($request);
    }
}
