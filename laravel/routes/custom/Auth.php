<?php

$router->group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
], function ($router) {
    $router->get('login', 'AuthController@showLoginForm')->name('login');
    $router->post('login', 'AuthController@postLogin');
    $router->get('logout', 'AuthController@logout');
});

//$router->group([
//    'prefix' => 'auth',
//    'namespace' => 'Auth',
//], function () {
//    //     Registration Routes...
//    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//    $this->post('register', 'Auth\RegisterController@register');
//});

$router->group([
    'prefix' => 'password',
    'namespace' => 'Auth',
], function ($router) {
    // Password Reset Routes...
    $router->get('email', 'ForgotPasswordController@showLinkRequestForm');
    $router->post('email', 'ForgotPasswordController@postEmail');
    $router->get('reset/{token}', 'ResetPasswordController@showResetForm');
    $router->post('reset', 'ResetPasswordController@reset');
});
