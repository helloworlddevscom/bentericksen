<?php

namespace Bentericksen\ViewComposers;

use App\User;
use Bentericksen\Layout\Navigation;
use Illuminate\Contracts\View\View;

class NavigationViewComposer
{
    /**
     * Binding data to the view.
     *
     * @param View $view
     * @return View
     * @throws \Throwable
     */
    public function compose(View $view)
    {
        $viewAs = session()->get('viewAs');
        $user = !empty($viewAs) ? User::find($viewAs) : $view->viewUser;

        return $view->with('navigation', new Navigation($user));
    }
}