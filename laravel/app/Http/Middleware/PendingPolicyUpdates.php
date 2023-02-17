<?php

namespace App\Http\Middleware;

use App\User;
use Bentericksen\PolicyUpdater\BusinessPolicyUpdate;
use Bentericksen\PolicyUpdater\UserPolicyUpdate;
use Closure;
use Auth;

/**
 * Class PendingPolicyUpdates.
 *
 * This is the code for the "policy_updates" middleware. It checks to see if the business
 * has any policy updates that need to be accepted, and saves them to the session for use
 * by the PolicyUpdateViewComposer.
 *
 * @see \Bentericksen\ViewComposers\PolicyUpdateViewComposer
 */
class PendingPolicyUpdates
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
        // TODO: could this be converted to use $viewAs->getUser() ?
        $viewUser = session('viewAs') ? User::find(session('viewAs')) : Auth::user();

        if ($viewUser->status == 'expired' ) {
            return $next($request);
        }
        
        $update = new UserPolicyUpdate($viewUser);

        if (count($update->setUpdatedPolicies())) {
            $new = $update->getPolicyTemplateUpdates();
            $old = $update->getCurrentPolicies($new);
            \Session::put('policyUpdatesPending', true);
            \Session::put('oldPolicies', $old);
            \Session::put('policyUpdates', $new);

            if (is_null(\Session::get('updateTotal'))) {
                \Session::put('updateTotal', count($new));
            }

            $request_path = $request->getPathInfo();

            // redirect if not accepting update or if dashboard page
            // to avoid a loop.
            if ($request_path !== '/user' &&
                strpos($request_path, 'acceptUpdate') === false &&
                strpos($request_path, 'policies') !== false &&
                !Auth::user()->hasRole('admin')) {
                return redirect('/user');
            }
        } else {
            \Session::put('policyUpdatesPending', false);
        }

        return $next($request);
    }
}
