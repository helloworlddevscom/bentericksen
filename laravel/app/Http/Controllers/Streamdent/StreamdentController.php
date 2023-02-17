<?php

namespace App\Http\Controllers\Streamdent;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Facades\StreamdentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Bentericksen\StreamdentServices\StreamdentAPIService;

class StreamdentController extends Controller
{
    private $loginAttemptCount = 1;

    /**
     * Get all clients
     *
     * @return Response
     */
    public function getClients() {
        try {
            return StreamdentService::getClients();
        } catch(\Exception $exception) {
            \Session::flash('error', $exception->getMessage());
        }
    }

    /**
     * Get client by business ID
     *
     * @param $businessId
     *
     * @return Response
     */
    public function getClientByBusinessId($businessId) {
        try {
            return StreamdentService::getClientByBusinessId($businessId);
        } catch (\Exception $exception) {
            \Session::flash('error', $exception->getMessage());
        }
    }

    /**
     * Update a client
     *
     * @param Request $request
     * @param optional $clientUpdates
     *
     * @return Response
     */
    public function updateClient(Request $request, $clientUpdates = null) {
        if ($clientUpdates != null) {
            try {
                return StreamdentService::updateClient([
                    'business_id' => $clientUpdates['business_id'],
                    'is_active' => $clientUpdates['is_active']
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        } else {
            try {
                return StreamdentService::updateClient([
                    'business_id' => $request->input('business_id'),
                    'is_active' => $request->input('is_active')
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        }
    }

    /**
     * Create client using an existing business
     *
     * @param Request $request
     * @param optional $clientUpdates
     *
     * @return Response
     */
    public function createClient(Request $request, $clientUpdates = null) {
        if ($clientUpdates != null) {
            try {
                return StreamdentService::createClient([
                    'business_id' => $clientUpdates['business_id']
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        } else {
            try {
                return StreamdentService::createClient([
                    'business_id' => $request->input('business_id')
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        }
    }

    /**
     * Get users
     *
     * @param $businessId
     *
     * @return Response
     */
    public function getUsers() {
        try {
            return StreamdentService::getUsers();
        } catch (\Exception $exception) {
            \Session::flash('error', $exception->getMessage());
        }
    }

    /**
     * Get Streamdent user by Streamdent or Bentericksen user ID
     *
     * @param $streamdent_id
     *
     * @return Response
     */
    public function getUserById($userId) {
        try {
            return StreamdentService::getUserById(StreamdentUser::where('streamdent_id', $userId)->first());
        } catch(\Exception $exception) {
            \Session::flash('error', $exception->getMessage());
        }
    }

    /**
     * Create a new user
     *
     * @param $request
     *
     * @return Response
     */
    public function createUser(Request $request, $userUpdates = null) {
        if ($userUpdates != null) {
            try {
                return StreamdentService::createUser([
                    'user_id' => $userUpdates['user_id']
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        } else {
            try {
                return StreamdentService::createUser([
                    'user_id' => $request->input('user_id')
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        }
    }

    /**
     * Update a user
     *
     * @param $businessId
     *
     * @return Response
     */
    public function updateUser(Request $request, $userUpdates = null) {
        if ($userUpdates != null) {
            try {
                return StreamdentService::updateUser([
                    'user_id' => $userUpdates['user_id'],
                    'password' => $userUpdates['password']
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        } else {
            try {
                return StreamdentService::updateUser([
                    'user_id' => $request->input('business_id'),
                    'password' => $request->input('password')
                ]);
            } catch (\Exception $exception) {
                \Session::flash('error', $exception->getMessage());
            }
        }
    }

    /**
     * Login a user
     *
     * @return Response
     */
    public function login() {
        $viewAs = session()->get('viewAs');

        $user = empty($viewAs) ? Auth::user() : User::find($viewAs);

        if (!$user->streamdentUser && !$user->streamdentJobPending()) {
            \Session::flash('error', $user->streamdentJobFailure());
            return back()->withInput();
        }

        if(!$user->streamdentUser && $user->streamdentJobPending()) {
            \Session::flash('error', 'Streamdent: Account pending.  Please check back momentarily.');
            return back()->withInput();
        }

        try {
            $streamdentService = new StreamdentAPIService();
            $result = $streamdentService->login($user->streamdentUser->login, Crypt::decryptString($user->streamdentUser->password));

            if (!isset($result['user']) && !empty(\Session::get('streamdent_session'))) {
                $url = config('streamdent.site');

                echo "<script>window.location.href='$url';</script>";

                exit;
            }

            if (isset($result['session_id'])) {
                \Session::put('streamdent_session', $result['session_id']);
            }

            $url = sprintf('%s/?user_id=%s&auth=%s', config('streamdent.site'), $result['user'], $result['token']);

            echo "<script>window.location.href='$url';</script>";

            exit;
        } catch (\Exception $exception) {
            if($exception->getMessage() === "There was a problem logging in, please try again." && $this->loginAttemptCount < 10) {
                Log::debug('StreamdentController - login attempt: ' . $this->loginAttemptCount);
                $this->loginAttemptCount++;
                sleep(5);
                $this->login();
            }

            Log::error($exception->getMessage());
            \Session::flash('error', $exception->getMessage());

            return back()->withInput();
        }
    }
}
