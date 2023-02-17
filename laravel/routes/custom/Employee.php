<?php

$router->group([
    'prefix' => 'employee',
    'namespace' => 'Employee',
    'middleware' => [ // This sequence matters, first check the employee count then the license (just for the employees)
        'auth',
        'employee.count',
        'license',
        'policy_updates',
    ],
], function ($router) {
    $router->get('/', ['as' => 'employee.dashboard', 'uses' => 'DashboardController@index']);
    $router->group(['middleware' => ['permissions', 'employee.status', 'status']], function ($router) {
        $router->get('policy/manual', 'PoliciesController@downloadManual');
        $router->get('info/{id}', 'InfoController@index');
        $router->post('info/{id}', 'InfoController@update');
        $router->post('info/{id}/dob-update', 'InfoController@updateDOB');
        $router->post('info/{id}/emergency-contacts-update', 'InfoController@updateEmergencyContacts');
        $router->post('info/{id}/driver-information-update', 'InfoController@updateDriverInformation');
        $router->get('time-off-request/{id}', 'TimeOffRequestController@index');
        $router->post('time-off-request/{id}/submit', 'TimeOffRequestController@submitRequest');
    });
});
