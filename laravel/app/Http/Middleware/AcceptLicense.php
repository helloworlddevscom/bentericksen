<?php

namespace App\Http\Middleware;

use App\Business;
use App\Role;
use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class AcceptLicense
{
    const CLIENT_LOCKED = 'client_locked';
    const EMPLOYEE_LOCKED = 'employee_locked';
    const LICENSE = 'license_agreement';

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $user_role;

    /**
     * @var string
     */
    private $business_status;

    /**
     * @var Business
     */
    private $business;

    /**
     * @var string
     */
    private $modal_type;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->user = $this->auth->user();

        // Avoid 'Trying to get property of non-object' error, for example when a user logs out
        if (is_object($this->user)) {
            $this->business = $this->user->business;
            $this->user_role = $this->user->getRole();

            if ($this->user_role !== Role::ADMIN && $this->user_role !== Role::CONSULTANT) {
                $this->business_status = $this->business->status;
            }
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $system_roles = $this->user->toArray();
        // this middleware does not apply to ADMIN or CONSULTANTS
        if ($this->user_role == Role::ADMIN || $this->user_role == Role::CONSULTANT) {
            return $next($request);
        }

        if ($this->business_status !== 'active') {
            $this->modal_type = $this->getModalType();
        } else {
            // If business is active, checking if owner accepted terms and conditions.
            // If not, employees are locked.
            if ($this->user_role == Role::EMPLOYEE) {
                $this->modal_type = ! $this->business->termsAccepted() ? self::EMPLOYEE_LOCKED : false;

                // If the terms were accepted by owner, check if the employee number was entered.
                // This logic happens on the Middleware (employee.count)
                if (session('employee_count_warning')) {
                    $this->modal_type = self::EMPLOYEE_LOCKED;
                }
            }
            // If user is primary user and hasn't accepted the terms and conditions, display terms and conditions
            if ($this->user->hasRole(['owner', 'manager'])) {
                $this->modal_type = $this->user->accepted_terms == '0000-00-00 00:00:00' ? self::LICENSE : false;
            }
        }

        // Checking if the terms and conditions/ASA expiration modal needs to be
        // displayed.
        if ($this->modal_type) {
            session()->put('modal_type', $this->modal_type);

            if ($request->getPathInfo() !== '/terms') {
                return redirect('terms');
            }
        }

        return $next($request);
    }

    /**
     * Returns the type of modal that should be displayed based on the User's role and
     * Business' status.
     *
     * @return bool|string
     */
    private function getModalType()
    {
        $isBusinessRenewed = $this->business_status == 'renewed' ? true : false;
        $bonusProEnabled = $this->business->is_bonus_pro_enabled;

        switch ($this->user_role) {
            case Role::CLIENT:
                if (! $isBusinessRenewed && $bonusProEnabled) {
                    $type = false;
                } else {
                    $type = $isBusinessRenewed ? self::LICENSE : self::CLIENT_LOCKED;
                }
                break;
            case Role::EMPLOYEE:
                $type = self::EMPLOYEE_LOCKED;
                break;
            case Role::CONSULTANT:
                $type = false;
                if (session('viewAsRole')) {
                    $type = $isBusinessRenewed ? self::LICENSE : self::CLIENT_LOCKED;
                }
                break;
            default:
                $type = false;
                break;
        }

        return $type;
    }
}
