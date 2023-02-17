<?php

use Illuminate\Support\Facades\Route;

$router->group([
    'middleware' => [
        'auth.admin',
    ],
], function ($router) {
    $router->get('/streamdent/users', 'Streamdent\StreamdentController@getUsers');
    $router->get('/streamdent/users/{userId}', 'Streamdent\StreamdentController@getUserById');
    $router->post('/streamdent/users', 'Streamdent\StreamdentController@createUser');
    $router->post('/streamdent/users/update', 'Streamdent\StreamdentController@updateUser');

    $router->get('/streamdent/clients/{clientId}', 'Streamdent\StreamdentController@getClientByBusinessId');
    $router->post('/streamdent/clients', 'Streamdent\StreamdentController@createClient');
    $router->post('/streamdent/clients/update', 'Streamdent\StreamdentController@updateUser');
});

$router->group([
    'middleware' => [
        'auth'
    ]
], function ($router) {
    $router->get('/streamdent/login', 'Streamdent\StreamdentController@login');
});
