<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Facades\StreamdentService;
use Illuminate\Http\Request;
use Session;
use Validator;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesUsers;

    protected $redirectTo = '/';
    protected $loginPath = '/auth/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function postLogin(Request $request)
    {
        $remember = $request->has('remember') ? true : false;
        // This works with the 'expire_on_close' config in: laravel/config/session.php

        $cookie = Cookie::forget('email');

        if (Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
            'status' => 'enabled',
            'can_access_system' => '1',
        ], false)) {   // Login condition above met
            // Don't use default Laravel "remember me" functionality
            // ... instead, set this cookie, so that JavaScript in laravel/resources/views/auth/login.blade.php
            // pre-populates the 'email' input field
            if ($remember === true) {
                // Need to set the 'httpOnly' param to false
                $cookie = Cookie::forever('email', $request->input('email'), null, null, false, false);
            }

            return redirect('/')->withCookie($cookie);
        }
        // Login condition above NOT MET.  Check for disable account.  This user is inactive in the system
        elseif (Auth::attempt([
                'email' => $request['email'],
                'password' => $request['password'],
                'status' => 'disabled',
                ])) {
            $disabled = 'Your account is disabled.  Please contact Bent Ericksen & Associates.';

            return redirect('/auth/login')->with('disabled', $disabled)->withCookie($cookie);
        } elseif (Auth::attempt([
                'email' => $request['email'],
                'password' => $request['password'],
                'status' => 'enabled',
                'can_access_system' => '0',
            ])) {
            $disabled = 'Your account is disabled.  Please contact Bent Ericksen & Associates.';

            return redirect('/auth/login')->with('disabled', $disabled)->withCookie($cookie);
        }

        if (preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i", $request->input('email'))) {
            return redirect($this->loginPath)
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'You entered an invalid username or password. Please try again.',
                ]);
        }

        try {
            $streamdentService = new StreamdentAPIService();
            $username = $streamdentService->parseUserName($request->input('email'));

            $result = $streamdentService->login($username, $request->input('password'));

            if (isset($result['user'])) {
                $url = sprintf('%s/?user_id=%s&auth=%s', config('streamdent.site'), $result['user'], $result['token']);

                echo "<script>window.location.href='$url';</script>";

                exit;
            }
        } catch(\Exception $e) {
            return redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                "Streamdent Login: {$e->getMessage()}"
            ]);
        }

        return redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'You entered an invalid username or password. Please try again.',
            ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
