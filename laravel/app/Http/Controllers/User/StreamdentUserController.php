<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Facades\StreamdentService;

use App\User;
use App\StreamdentUser;
use App\StreamdentAPIUserSchema;

use Auth;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Bentericksen\ViewAs\ViewAs;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class StreamdentUserController extends Controller
{
    private $business;
    private $viewAs;

    public function __construct(ViewAs $viewAs)
    {
        $this->viewAs = $viewAs;
        $this->user = \User::findOrFail($viewAs->getUserId());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localUser = StreamdentUser::where('business_id', $this->user->business->id)
        ->where('user_id', null)->first();

        if (empty($localUser)) {
            return view('user.streamdent.index', [
                'streamentUser' => new StreamdentAPIUserSchema()
            ]);
        }

        try {
            $streamdentService = new StreamdentAPIService();
            $response = $streamdentService->getUserById($localUser);

            if (!$response['success']) {
                return view('user.streamdent.index', [
                    'streamentUser' => new StreamdentAPIUserSchema()
                ]);
            }

            $user = new StreamdentAPIUserSchema($response['user']);

            try {
                $user->password = Crypt::decryptString($localUser->password);
            } catch(\Exception $e) {
                $user->password = $localUser->password;
            }

            return view('user.streamdent.index', [
                'streamentUser' => $user
            ]);

        } catch(\Exception $e) {
            \Session::flash('error', $e->getMessage());

            return view('user.streamdent.index', [
                'streamentUser' => new StreamdentAPIUserSchema()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $streamdentService = new StreamdentAPIService();
            $user = $streamdentService->createUser(array_merge([
                'business_id' => $this->user->business->id,
            ], $request->except(['_token'])));
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
        }

        return redirect()->route('streamdent.users.index');
    }

    /**
     * Display the list of .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $streamdentService = new StreamdentAPIService();
            $user = StreamdentUser::where('streamdent_id', $id)->first();
            $response = $streamdentService->getUserById($user);

            if (!$response['success']) {
                return redirect()->route('streamdent.users.index');
            }

            return view('user.streamdent.index', [
                'streamentUser' => new StreamdentAPIUserSchema($response['user'])
            ]);
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
        }

        return redirect()->route('streamdent.users.index', [
            'streamentUser' => new StreamdentAPIUserSchema()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $streamdentService = new StreamdentAPIService();
            $response = $streamdentService->updateUser($request->except(['_token', '_method']));
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
        }

        return redirect()->route('streamdent.users.index', [
            'streamentUser' => new StreamdentAPIUserSchema()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $streamdentService = new StreamdentAPIService();
            $streamdentService->updateUser(array_merge([
                'business_id' => $this->user->business->id,
            ], $request->except(['_token'])));
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
        }


        return redirect()->route('streamdent.users.index');
    }
}
