<?php

$router->group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => [
        'auth.admin',
        'shared.inertia.admin'
    ],
], function ($router) {
    $router->group(['middleware' => []], function ($router) {
        $router->get('/', 'BusinessController@index');
        $router->get('/business-index', 'BusinessController@getBusinesses');

        /*
         * Business' Routes
         */
        $router->get('business-export/{type}/filter', '\App\Http\Controllers\Admin\ExportFilterController@index');
        $router->post('business-export/{type}/list', '\App\Http\Controllers\Admin\ExportFilterController@exportList');
        $router->get('business/duplicate_entry', 'BusinessController@duplicate');
        $router->get('business/{id}/view-as', 'BusinessController@viewAs');
        $router->resource('business', 'BusinessController', ['as' => 'admin']);

        /*
         * Policy Updaters
         */
        $router->get('policies/updates', 'PolicyUpdaterController@index');
        $router->get('policies/updates/create', 'PolicyUpdaterController@create');
        $router->post('policies/updates/create', 'PolicyUpdaterController@create');
        $router->get('policies/updates/{id}', 'PolicyUpdaterController@show');
        $router->get('policies/updates/{id}/delete', 'PolicyUpdaterController@destroy');
        $router->get('policies/updates/{id}/export/{which}', '\App\Http\Controllers\HelperController@exportPolicyUpdateEmailsList');
        $router->get('policies/updates/{id}/trigger', 'PolicyUpdaterController@triggerPolicyUpdate');

        /*
         * Policy sorting
         */
        // sort policy templates within a category
        $router->get('policies/sort/{id}', 'PoliciesController@categorySorts');
        $router->put('policies/sort/templates', [
            'as' => 'sortTemplateUpdate',
            'uses' => 'PoliciesController@categorySortUpdate',
        ]);

        // sort the policy categories themselves
        $router->get('policies/sort', 'PoliciesController@sorts');
        $router->put('policies/sort', ['as' => 'sortUpdate', 'uses' => 'PoliciesController@sortUpdate']);

        /*
         * Policy templates
         */
        $router->get('policies/{id}/changeStatus', 'PoliciesController@changeStatus');
        $router->post('policies/{id}/status', 'PoliciesController@status');

        // policy templates CRUD
        $router->resource('policies', 'PoliciesController', ['as' => 'admin']);

        /* End Policy Templates */

        /*
         * Categories - currently under construction
         */
        $router->get('categories/create', 'CategoriesController@create');
        $router->post('categories', 'CategoriesController@store');
        $router->resource('categories', 'CategoriesController', ['as' => 'admin']);

        /*
         * JOB Description
         */
        $router->resource('job-descriptions', 'JobDescriptionController', ['as' => 'admin']);

        /*
         * FAQS
         */
        $router->post('faqs/search', 'FaqsController@indexSearch');
        $router->post('faqs/category/create', 'FaqsController@categoryCreate');
        $router->delete('faqs/{id}/delete', 'FaqsController@destroy');
        $router->resource('faqs', 'FaqsController', ['as' => 'admin']);

        /*
         * Forms
         */
        $router->get('forms', 'FormsController@index');
        $router->get('forms/create', 'FormsController@create');
        $router->get('forms/{id}/edit', 'FormsController@edit');
        $router->get('forms/{id}/changeStatus', 'FormsController@changeStatus');
        $router->post('forms', 'FormsController@store');
        $router->put('forms/{id}', 'FormsController@update');
        $router->get('forms/{id}/preview', 'FormsController@preview');
        $router->delete('forms/{id}', 'FormsController@destroy');

        /*
         * Other
         */
        $router->post('updateUser', 'AdminController@updateUser');
        $router->get('user/{id}/view-as', 'BusinessController@viewAsUser');
        $router->get('user/{id}/emails', 'EmailLogController@index');
        $router->get('/emails/{id}', 'EmailLogController@show');
        $router->get('login-list', 'AdminController@loginList');
        $router->get('users-index', 'AdminController@getUsers');
        $router->get('email-list', 'EmailLogController@emailList');
        $router->get('email-index', 'EmailLogController@getEmails');
        $router->get('login-list/export', '\App\Http\Controllers\HelperController@exportUserList');
        $router->get('export-clients', [
            'as' => 'admin_business_export',
            'uses' => 'BusinessController@index',
        ]);
        $router->resource('help', 'HelpController', ['as' => 'admin']);

    });

});
