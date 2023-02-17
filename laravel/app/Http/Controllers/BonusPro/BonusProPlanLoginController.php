<?php

namespace App\Http\Controllers\BonusPro;

use App\BonusPro\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusProPlanLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:bonuspro');
    }

    protected function guard()
    {
        return Auth::guard('bonuspro');
    }

    public function login(Request $request, Plan $plan)
    {
        $credentials = [
            'id' => $plan->id,
            'password' => $request->input('password'),
        ];

        $result = Auth::guard('bonuspro')->attempt($credentials);

        if ($result && $plan->completed) {
            return redirect()->route('bonuspro.edit', $plan->id);
        }

        if ($result) {
            return redirect()->route('bonuspro.resume', ['id' => $plan->id]);
        }

        return redirect()->route('bonuspro.plan.login', ['plan' => $plan->id]);
    }

    public function showLoginForm(Request $request, Plan $plan)
    {
        return view('auth.bonuspro.plan.login')->with(['plan' => $plan]);
    }
}
