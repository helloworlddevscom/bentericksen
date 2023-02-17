<?php

namespace App\Http\Controllers\BonusPro;

use App\BonusPro\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class BonusProPlanResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * What plan password is being reset.
     *
     * @var App\BonusPro\Plan
     */
    protected $plan;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:bonuspro');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, Plan $plan, $token = null)
    {
        return view('auth.bonuspro.plan.reset')->with(
            [
                'token' => $token,
                'plan' => $plan,
            ]
        );
    }

    protected function credentials(Request $request)
    {
        return [
            'id' => $request->input('plan'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'token' => $request->input('token'),
        ];
    }

    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);

        $this->plan = $user;
    }

    protected function guard()
    {
        return Auth::guard('bonuspro');
    }

    protected function broker()
    {
        return Password::broker('bonuspro-plans');
    }

    protected function redirectTo()
    {
        if ($this->plan->complete) {
            return route('bonuspro.resume', ['id' => $this->plan->id]);
        } else {
            return route('bonuspro.edit', ['id' => $this->plan->id]);
        }
    }
}
