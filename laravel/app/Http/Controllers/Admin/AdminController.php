<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of user login dates.
     *
     * @return Response
     */
    public function loginList(Request $request)
    {
      return $this->inertia('Admin/LoginList/Index', [
        '_response' => $this->getUsers($request)
      ]);
    }

    public function getUsers(Request $request)
    {
        $business = $request->input('business_name');
        $contact = $request->input('contact_name');
        $role = $request->input('role');
        $email = $request->input('email');

        $sort = $request->input('sort') ?? 'business.name';
        $sortOrder = $request->input('sort_order') ?? 'asc';
        $from = $request->input('from');
        $to = $request->input('to');

        $users = User::select([
            'business.name',
            'users.id',
            'users.business_id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.last_login',
            'users.status',
        ])
        ->leftJoin('business', 'users.business_id', '=', 'business.id')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->with('roles');

        if (! empty($business)) {
            $users = $users->where('business.name', 'like', "%$business%");
        }

        if (! empty($contact)) {
            $users = $users->where(function ($query) use ($contact) {
                $query->where('users.first_name', 'like', "%$contact%")
                    ->orWhere('users.last_name', 'like', "%$contact%");
            });
        }

        if (! empty($email)) {
            $users = $users
                ->where('users.email', 'like', "%$email%");
        }

        if (! empty($to) && ! empty($from)) {
            $users = $users->whereBetween('users.last_login', [$from, $to]);
        }

        if (! empty($role)) {
            $users = $users->where('role_user.role_id', $role);
        }

        if (! empty($sort) && ! empty($sortOrder)) {
            $users = $users
                ->orderBy($sort, $sortOrder);
        }

        $users = $users
            ->paginate(10);

        return $users;
    }

    /**
     * Updating user's status.
     *
     * @param Request $request
     * @return string
     */
    public function updateUser(Request $request)
    {
        $id = $request->input('user_id');

        $user = User::find($id);

        if ($user->status == 'enabled') {
            $user->status = 'disabled';
        } else {
            $user->status = 'enabled';
        }

        $user->save();

        // Returning opposite value of status for setting button text
        return $user->status == 'enabled' ? 'disable' : 'enable';
    }
}
