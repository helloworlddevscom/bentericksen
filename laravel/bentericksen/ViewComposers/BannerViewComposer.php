<?php
namespace Bentericksen\ViewComposers;

use Illuminate\Contracts\View\View;
use App\User;
use App\Business;
use Inertia\Inertia;

/**
 * Class BannerViewComposer
 *
 * Adds some data to the Views that is used for rendering the yellow admin/consultant banner at the top
 * of the screen when "Viewing As" a user.
 *
 * This also injects certain variables like $viewUser into the templates.
 *
 * @package Bentericksen\ViewComposers
 */
class BannerViewComposer
{

    protected $viewUser;

    protected $impersonated;

    private $userBusiness;

    private $manual;
    

    /**
     * Create a new wrap composer.
     */
    public function __construct()
    {
        // for permissions to be correct, this needs to be the actual logged in user,
        // not the user being impersonated.
        $this->viewUser = \Auth::user();

        if ($this->viewUser->business_id) {
            $this->userBusiness = Business::find($this->viewUser->business_id);
            $this->manual = $this->userBusiness->manual;
        }
    }

    public function setData() {
      $banner = "";
      $impersonated = null;
      $manual = null;
      $viewAs = session()->get('viewAs');

      if (!empty($viewAs)) {
          $impersonated = $user = User::with('acl')->with('business.asa')->find($viewAs);
          $manual = $user->business ? $user->business->manual : null;
          switch (session()->get('viewAsRole')) {
              case 'admin':
                  $banner = "<div class='alert alert-warning alert-dismissible' role='alert'><a href='/admin' class='close'><span aria-hidden='true'>&times;</span></a><strong>Admin Dashboard</strong> <p class='pull-right'>Viewing Interface as [" . $user->full_name . "]</p></div>";
                  break;

              case 'consultant':
                  $banner = "<div class='alert alert-warning alert-dismissible' role='alert'><a href='/consultant' class='close'><span aria-hidden='true'>&times;</span></a><strong>Consultant Dashboard</strong> <p class='pull-right'>Viewing Interface as [" . $user->full_name . "]</p></div>";
                  break;
          }
      } else {
          $impersonated = $this->viewUser;
      }

      return [
        'banner' => $banner,
        'impersonated' => $impersonated,
        'manual' => $manual,
        'userBusiness' => $this->userBusiness,
        'viewUser' => $this->viewUser
      ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @todo: Move the markup to a blade file.
     *
     * @return void
     */
    public function compose(View $view)
    {
      $data = $this->setData();

      $view->with('banner', $data['banner'])
          ->with('viewUser', $data['viewUser'])
          ->with('impersonated', $data['impersonated'])
          ->with('manual', $data['manual']);
    }
}
