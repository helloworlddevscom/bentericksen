<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BonusProPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('id') ?? $request->route('bonuspro');

        if (! Auth::guard('bonuspro')->check() || Auth::guard('bonuspro')->id() != $id) {
            return redirect()->route('bonuspro.plan.login', ['plan' => $id]);
        }

        return $next($request);
    }
}
