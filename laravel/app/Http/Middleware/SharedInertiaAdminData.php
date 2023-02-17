<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SharedInertiaAdminData
{

  public function handle($request, Closure $next)
  {
    Inertia::share( 'user', Auth::user() );
    
    return $next($request);
  }
}