<?php

namespace App\Http;

use App\Http\Middleware\AcceptLicense;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\BonusPro;
use App\Http\Middleware\BonusProPlan;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\Hrdirector;
use App\Http\Middleware\NumberOfEmployees;
use App\Http\Middleware\PendingPolicyUpdates;
use App\Http\Middleware\Permissions;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\Status;
use App\Http\Middleware\EmployeeStatus;
use App\Http\Middleware\SharedInertiaAdminData;
use App\Http\Middleware\SharedInertiaUserData;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        Middleware\VerifyCsrfToken::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            'auth',
            'bindings',
            'license',
            'status',
            'permissions',
            \App\Http\Middleware\HandleInertiaRequests::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'license' => AcceptLicense::class,
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'auth.admin' => AdminAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'employee.count' => NumberOfEmployees::class,
        'permissions' => Permissions::class,
        'status' => Status::class,
        'policy_updates' => PendingPolicyUpdates::class,
        'bonuspro' => BonusPro::class,
        'bonuspro.plans' => BonusProPlan::class,
        'hrdirector' => Hrdirector::class,
        'employee.status' => EmployeeStatus::class,
        'shared.inertia.admin' => SharedInertiaAdminData::class,
        'shared.inertia.user' => SharedInertiaUserData::class
    ];
}
