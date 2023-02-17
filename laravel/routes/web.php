<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/***
Route::get('/', function () {
    return Inertia::render('Home');
});
***/

include base_path('routes/custom/Auth.php');
include base_path('routes/custom/Admin.php');
include base_path('routes/custom/Consultant.php');
include base_path('routes/custom/Employee.php');
include base_path('routes/custom/Streamdent.php');

$router->get('home', function () {
    return redirect('/');
});

//user middleware on this to ensure person has permissions for this
$router->group(['middleware' => ['auth', 'license']], function ($router) {
    $router->get('terms', 'TermsController@index');
    $router->post('terms', 'TermsController@accept');

    $router->get('/', function () {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.business.index');
        }

        if (Auth::user()->hasRole('consultant')) {
            return redirect()->route('consultant.dashboard');
        }

        if (Auth::user()->hasRole('owner')) {
            return redirect()->route('user.dashboard');
        }

        if (Auth::user()->hasRole('manager')) {
            return redirect()->route('user.dashboard');
        }

        if (Auth::user()->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }

        // temporary: logging out users without a role.
        return redirect('auth/logout');
    });

    $router->get('/ping', function() {
        return [];
    });
});

/**
 *  Payment Routes
 */
$router->group([
    'prefix' => 'payment',
    'middleware' => ['auth']
  ], function() {

  Route::resource('/cards', 'Payment\CardController');
  Route::resource('/accounts', 'Payment\BankAccountController');
  Route::resource('/subscriptions', 'Payment\SubscriptionController');
  Route::post('/subscribe', 'Payment\SubscriptionController@subscribe');
  Route::resource('/products', 'Payment\ProductController');
  Route::resource('/customers', 'Payment\CustomerController');
  Route::post('/accounts/{account}','Payment\BankAccountController@verifyAccount');
  Route::get('/setup', 'Payment\PaymentController@setupPayments');
  Route::post('/create-transaction', 'Payment\PaymentController@createTransaction');
  Route::post('/add-instrument', 'Payment\PaymentController@addInstrument');
  Route::post('/update-instrument', 'Payment\PaymentController@updateInstrument');
  Route::post('/delete-instrument', 'Payment\PaymentController@deleteInstrument');
  Route::post('/invoice-subscription', 'Payment\PaymentController@invoiceSubscription');
  Route::post('/cancel-subscription', 'Payment\PaymentController@cancelSubscription');

});

Route::post('/stripe-webhook', 'Payment\WebhookController@handleWebhook');
