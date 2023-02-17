<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Business;
use App\BusinessPermission;
use App\Http\Controllers\Controller;
use Bentericksen\Models\Permissions\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionController.
 *
 * Controller for the business permissions editing page. This lets
 * business owners customize permissions by employee type.
 *
 * @see \App\BusinessPermission
 * @see \Bentericksen\Models\Permissions\Permissions
 */
class PermissionController extends Controller
{
    private $permissions;

    private $id;

    private $businessObject;

    public function __construct(Permissions $permissions)
    {
        $this->permissions = $permissions;
        $this->id = $this->permissions->permissions->id;

        $this->businessObject = Business::findOrFail($this->id);
    }

    /**
     * Display the permissions editing form for the current user's business
     * (GET /user/permissions).
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $viewAs = session()->get('viewAs');

        $user = empty($viewAs) ? Auth::user() : User::find($viewAs);

        return view('user.permissions')
            ->with('user', $user)
            ->with('permissions', $this->permissions)
            ->with('businessObject', $this->businessObject); 
    }

    /**
     * Saves the permissions form
     * (POST /user/permissions/submit).
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit(Request $request)
    {
        $permissionsRow = BusinessPermission::findOrFail($this->id);

        $permissions = [];

        foreach ($request->all() as $key => $value) {
            if ($key == '_token') {
                continue;
            }

            $permissions[$key] = $value;
        }

        $permissionsRow->update($permissions);

        return redirect('/user/permissions/')->with('status', 'Permissions updated!');
    }
}
