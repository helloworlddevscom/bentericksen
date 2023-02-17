<?php

namespace App\Http\Middleware;

use App\BusinessPermission;
use Closure;

class Permissions
{
    protected $routes = [
        'm120' => 'user/policies',
        'm121' => 'user',
        'm140' => 'user/employees',
        'm160' => 'user/job-descriptions',
        'm180' => 'user/forms',
        'm200' => 'user/faqs',
        'm201' => 'user/calculators',
        'm260' => 'user/account',
        'e160' => 'employee/job-descriptions',
        'e200' => 'employee/time-off-request/',
        'e221' => 'employee/info/',
    ];

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ignore dashboard route.
        if ($request->routeIs('user.dashboard')) {
            return $response;
        }

        $permissions = BusinessPermission::where('business_id', $request->user()->business_id)
            ->first();

        if ($request->user()->hasRole('manager')) {
            if ($request->path() === 'user/permissions') {
                return redirect()->route('user.dashboard');
            }

            if (! $request->user()->permissions('m100')) {
                return redirect()->route('user.dashboard');
            }

            foreach ($permissions->toArray() as $permissionKey => $permissionValue) {
                if (! array_key_exists($permissionKey, $this->routes)) {
                    continue;
                }

                $returnValue = $request->user()->permissions($permissionKey);
                if (($returnValue === false || $returnValue === 'View Only' || $returnValue === 'No Access') &&
                    $request->path() === $this->routes[$permissionKey]) {
                    return redirect()->route('user.dashboard');
                }
            }
        }

        if ($request->user()->hasRole('employee')) {
            $this->routes['e200'] .= $request->user()->id;
            $this->routes['e221'] .= $request->user()->id;

            if (! $request->user()->permissions('e100')) {
                return redirect()->route('employee.dashboard');
            }

            foreach ($permissions as $permissionKey => $permissionValue) {
                foreach ($this->routes as $routeKey => $routeValue) {
                    if ($permissionKey === $routeKey) {
                        $returnValue = $request->user()->permissions($routeKey);

                        if ($returnValue === false && $request->path() === $routeValue) {
                            return redirect()->route('employee.dashboard');
                        }
                    }
                }
            }
        }

        return $response;
    }
}
