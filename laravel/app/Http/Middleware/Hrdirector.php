<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Hrdirector
{
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
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
        if ($this->user->bonuspro_only && $this->user->business->is_bonus_pro_enabled) {
            return redirect('/bonuspro');
        }

        $path = $request->getRequestUri();

        if ($path == '/user') {
            return $next($request);
        }

        session()->put('hrdirector_enabled', $this->user->business->hrdirector_enabled);

        if (! $this->user->business->hrdirector_enabled) {
            return redirect('/user')->withErrors([
                'You don\'t have access to HR Director. Please contact Customer Service.',
            ]);
        }

        return $next($request);
    }
}
