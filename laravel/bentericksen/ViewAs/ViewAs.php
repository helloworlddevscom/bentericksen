<?php

namespace Bentericksen\ViewAs;

use App\User;
use Auth;

class ViewAs
{

    /**
     * @var \App\User
     */
    protected $user;

    public function __construct()
    {
        if (session()->get('viewAs') && session()->get('viewAs') != '' && session()->get('viewAs') != null) {
            $this->user = User::with('business')->find(session()->get('viewAs'));
        } else {
            $this->user = Auth::user();
        }
    }


    /**
     * Returns the ID of the currently active user.
     *
     * Under normal conditions, this returns the id of the user account that the user logged in with.
     * When using "View As" (i.e., the yellow "Viewing interface as" banner appears), this returns
     * the id of the user account that is being impersonated.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user ? $this->user->id : null;
    }


    /**
     * Gets the currently active user.
     *
     * Under normal conditions, this returns the regular user account that the user logged in with.
     * When using "View As" (i.e., the yellow "Viewing interface as" banner appears), this returns
     * the user account that is being impersonated.
     *
     * @return \App\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns the actual user account the user originally logged in with. (Not the one they're impersonating.)
     * If no impersonation is occurring, this returns the same object as getUser().
     *
     * Used when you need to check for admin/consultant permissions.
     */
    public function getActualUser() {
        return Auth::user();
    }
}
