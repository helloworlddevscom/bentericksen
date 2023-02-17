<?php

namespace App\Providers;

use Bentericksen\Help\Help;
use Bentericksen\ViewComposers\BannerViewComposer;
use Bentericksen\ViewComposers\BusinessEmployeeCountViewComposer;
use Bentericksen\ViewComposers\CreateBenefitViewComposer;
use Bentericksen\ViewComposers\CreateManualViewComposer;
use Bentericksen\ViewComposers\EmployeePolicyManualViewComposer;
use Bentericksen\ViewComposers\NavigationViewComposer;
use Bentericksen\ViewComposers\PolicyFinalizationViewComposer;
use Bentericksen\ViewComposers\PolicyRejectModificationsViewComposer;
use Bentericksen\ViewComposers\PolicyUpdateViewComposer;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // The View Composers bind extra variables to certain views.
        View::composer([
            'user.wrap',
            'user.index',
            'consultant.wrap',
            'employee.*',
            'user.policies.*', // @todo: remove. Need to remove all references to $viewUser from every blade template.
            'user.employees.*', // @todo: remove. Need to remove all references to $viewUser from every blade template.
        ], BannerViewComposer::class);
        View::composer(['admin.*', 'user.*', 'consultant.*', 'employee.*'], Help::class);
        View::composer(['user.wrap', 'user.policies.list'], PolicyFinalizationViewComposer::class);
        View::composer(['user.wrap', 'user.policies.list', 'employee.*'], PolicyUpdateViewComposer::class);
        View::composer(['user.wrap'], CreateManualViewComposer::class);
        View::composer(['user.wrap'], CreateBenefitViewComposer::class);
        View::composer(['user.wrap'], BusinessEmployeeCountViewComposer::class);
        View::composer(['employee.wrap'], EmployeePolicyManualViewComposer::class);
        View::composer(['user.policies.edit'], PolicyRejectModificationsViewComposer::class);
        View::composer(['shared.navigation'], NavigationViewComposer::class);
        View::composer('*', function($view) {
          View::share('view_name', str_replace( '.', '_', $view->getName() ));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
