<?php

$router->group([
    'prefix' => 'consultant',
    'namespace' => 'Consultant',
    'middleware' => [
        'auth',
        'license',
    ],
], function ($router) {
    $router->group(['middleware' => ['status']], function ($router) {
        $router->get('/business/{id}/view-as', ['as' => 'consultant.business.view_as', 'uses' => 'ConsultantController@viewAs']);
    });
    $router->get('/', ['as' => 'consultant.dashboard', 'uses' => 'ConsultantController@index']);
});
