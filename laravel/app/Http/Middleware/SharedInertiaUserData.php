<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Bentericksen\ViewComposers\BannerViewComposer;

class SharedInertiaUserData
{

  public function handle($request, Closure $next)
  {
    $bannerViewComposer = new BannerViewComposer();
    $bannerViewComposerData = $bannerViewComposer->setData();
    if(config('app.env') !== "production") {
      $stripePubKey = config('stripe.api.pub_key');
    } else {
      $stripePubKey = config('stripe.api.prod.pub_key');
    }

    Inertia::share( 'user', Auth::user() );
    Inertia::share( 'banner', $bannerViewComposerData['banner'] );
    Inertia::share( 'viewUser', $bannerViewComposerData['viewUser'] );
    Inertia::share( 'impersonated', $bannerViewComposerData['impersonated'] );
    Inertia::share( 'manual', $bannerViewComposerData['manual'] );
    Inertia::share( 'policy_updates_run', Session::get('policyUpdatesPending') );
    Inertia::share( 'policies_pending', $bannerViewComposerData['impersonated']->business->hasPendingPolicies() );
    Inertia::share( 'policy_updates', Session::get('policyUpdates') ?? []);
    Inertia::share( 'employee_count_warning', session()->get('employee_count_warning') );
    Inertia::share( 'manual_regenerate', !$bannerViewComposerData['impersonated']->business->manual && Session::get('manualRegenerate') );
    Inertia::share( 'benefit_create_warning', is_null($bannerViewComposerData['impersonated']->business->manual) );
    Inertia::share( 'config', [
      'stripe_pk' => $stripePubKey
    ]);

    return $next($request);
  }
}