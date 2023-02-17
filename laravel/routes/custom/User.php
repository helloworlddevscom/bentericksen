<?php

/**
 * Routes that require HR Director access.
 */
$router->group([
    'middleware' => [
        'hrdirector',
        'employee.count',
        'policy_updates',
        'shared.inertia.user',
    ],
], function ($router) {

    /*
     * Policies routes
     */
    $router->post('policies/wizard/{step}', [
        'as' => 'wizard.update',
        'uses' => 'PoliciesController@wizardUpdate',
    ]);
    $router->get('policies/wizard', 'PoliciesController@wizard');
    $router->post('policies/acceptUpdate/{id}', 'PoliciesController@acceptUpdate');
    $router->get('policies/reset', 'PoliciesController@reset');
    $router->get('policies/{id}/select', 'PoliciesController@select');
    $router->put('policies/{type}/select', 'PoliciesController@selectUpdate');
    $router->put('policies/{id}/restore', 'PoliciesController@restore');
    $router->post('policies/addCategory', [
        'as' => 'user.policies.addCategory',
        'uses' => 'PoliciesController@addCategory',
    ]);
    $router->get('policies/{id}/toggleStatus', 'PoliciesController@toggleStatus');
    $router->get('policies/parseContent', 'PoliciesController@parseContent');
    $router->post('policies/parseContent', 'PoliciesController@parseContent');
    $router->get('policies/sort', 'PoliciesController@sorting');
    $router->post('policies/sort', 'PoliciesController@updateSorting');
    $router->get('policies/sortRestore', 'PoliciesController@restoreSorting');
    $router->get('policies/benefits-summary', 'PoliciesController@downloadBenefitSummary');
    $router->get('policies/manual', 'PoliciesController@downloadManual');
    $router->get('policies/createManual', 'PoliciesController@createManual');
    $router->post('policies/createManualToken', 'PoliciesController@createManualToken');
    $router->get('policies/{id}/resetSelect', 'PoliciesController@resetSelect');
    $router->get('policies/{policy}/compare', 'PoliciesController@compare')->middleware('auth.admin');
    $router->resource('policies', 'PoliciesController', ['as' => 'user']);

    /*
     * Time Off Requests
     */
    $router->get('employees/time-off-requests', 'TimeOffController@index');
    $router->get('employees/time-off-requests/view/{viewId}', 'TimeOffController@index');
    $router->post('employees/time-off-requests', 'TimeOffController@store');
    $router->get('employees/time-off-requests/{id}', 'TimeOffController@view')->name('editTimeOff');
    $router->post('employees/time-off-requests/{id}/editRequest', 'TimeOffController@editRequest');
    $router->post('employees/timeoff/{id}', 'TimeOffController@timeOffStatusUpdate');
    $router->post('employees/timeoff/{id}/deleteStatus', 'TimeOffController@timeOffDeleteStatus');

    /*
     * Employee Routes
     */
    $router->get('employees/excel', 'EmployeesController@excel');
    $router->post('employees/excel', 'EmployeesController@excelImport');
    $router->get('employees/excel/download', 'EmployeesController@excelDownload');
    $router->put('employees/additionalEmployeesUpdate', 'EmployeesController@additionalEmployeesUpdate');
    $router->get('employees/wizard/staff', 'EmployeesWizardController@staff');
    $router->post('employees/wizard/staff', 'EmployeesWizardController@staffExcelUpload');
    $router->get('employees/wizard/initial-benefits', 'EmployeesWizardController@initialBenefits');
    $router->get('employees/wizard/accessibility', 'EmployeesWizardController@accessibility');
    $router->post('employees/wizard/accessibility', 'EmployeesWizardController@accessibilityUpdate');
    $router->get('employees/wizard/complete', 'EmployeesWizardController@complete');
    $router->get('employees/wizard', 'EmployeesWizardController@index');
    $router->post('employees/{id}/activation', 'EmployeesController@sendEmailReminder');
    /*
     * End Employees
     */

    /*
     * Job Descriptions
     */
    $router->delete('job-descriptions/{id}', 'JobDescriptionController@destroy');
    $router->get('job-descriptions/create', [
        'as' => 'user.job-description.create',
        'uses' => 'JobDescriptionController@create',
    ]);
    $router->get('job-descriptions/{id}', 'JobDescriptionController@show');
    $router->get('job-descriptions', [
        'as' => 'user.job-description.index',
        'uses' => 'JobDescriptionController@index',
    ]);
    $router->get('job-descriptions/{id}/edit', [
        'as' => 'user.job-description.edit',
        'uses' => 'JobDescriptionController@edit',
    ]);
    $router->get('job-descriptions/{id}/print', 'JobDescriptionController@printJob');
    $router->post('job-descriptions/store', [
        'as' => 'user.job-descriptions.store',
        'uses' => 'JobDescriptionController@store',
    ]);
    $router->patch('job-descriptions/{id}', 'JobDescriptionController@update');
    $router->get('job-descriptions/{id}/duplicate', 'JobDescriptionController@duplicate');
    /*
     * End Job Descriptions
     */

    /*
     * Job Titles
     */
    $router->get('job-titles', [
        'as' => 'user.job-titles.index',
        'uses' => 'JobTitleController@index',
    ]);
    $router->get('job-titles/create', [
        'as' => 'user.job-titles.create',
        'uses' => 'JobTitleController@create',
    ]);
    $router->get('job-titles/{id}/edit', [
        'as' => 'user.job-titles.edit',
        'uses' => 'JobTitleController@edit',
    ]);
    $router->put('job-titles/{id}', [
        'as' => 'user.job-titles.update',
        'uses' => 'JobTitleController@update',
    ]);
    $router->post('job-titles', [
        'as' => 'user.job-titles.store',
        'uses' => 'JobTitleController@store',
    ]);
    $router->delete('job-titles/{id}', [
        'as' => 'user.job-titles.destroy',
        'uses' => 'JobTitleController@destroy',
    ]);
    /*
     * End Job Titles
     */

    /*
     * Report Tab
     */
    $router->get('reports', [
        'as' => 'user.reports.index',
        'uses' => 'ReportsController@index',
    ]);
    /*
     * End Report Tab
     */

    /*
     * Account Tab
     */
    $router->get('account', [
        'as' => 'user.account.index',
        'uses' => 'AccountController@index',
    ]);
    $router->get('account/{id}/edit', [
        'as' => 'user.account.edit',
        'uses' => 'AccountController@edit',
    ]);
    $router->put('account/{id}', [
        'as' => 'user.account.update',
        'uses' => 'AccountController@update',
    ]);
    $router->put('account/secondary/{id}', [
        'as' => 'user.account.secondary',
        'uses' => 'AccountController@secondary',
    ]);
    $router->delete('account/secondary/{id}', 'AccountController@deleteSecondary');
    /*
     * End Account Tab
     */

    /*
     * Forms
     */
    $router->put('forms/{id}', 'FormsController@update');
    $router->get('forms/{id}/restore', 'FormsController@restore');
    $router->post('forms/{id}/restore', 'FormsController@restore');
    $router->get('forms/{id}/edit', 'FormsController@edit');
    $router->get('forms/{id}/preview', 'FormsController@preview');
    $router->get('forms/{id}/print', 'FormsController@printForm');
    $router->get('forms/reset', 'FormsController@reset');
    $router->get('forms', 'FormsController@index');
    /* End Forms */

    /*
     * Streamdent SOPs
     */
    $router->put('streamdent/users/update/{id}', 'StreamdentUserController@update')->name('streamdent.users.update');
    $router->get('streamdent/users/{id}/edit', 'StreamdentUserController@edit');
    $router->get('streamdent/users/reset', 'StreamdentUserController@reset');
    $router->post('streamdent/users', 'StreamdentUserController@store')
        ->name('streamdent.users.store');
    $router->get('streamdent/users/show', 'StreamdentUserController@show');
    $router->get('streamdent/users', 'StreamdentUserController@index')->name('streamdent.users.index');
    /* End Streamdent SOPs */



    /*
     * Other
     */
    $router->resource('licensure-certifications', 'LicensureController', ['as' => 'user']);
});

/*
 * Routes that does not require HR Director access, i.e. routes that needs to be accessible from BonusPro
 */
$router->group([
    'middleware' => [
        'license',
        'employee.count',
        'policy_updates',
        'shared.inertia.user',
    ],
], function ($router) {
    $router->get('/', ['as' => 'user.dashboard', 'uses' => 'DashboardController@index']);
    $router->post('dashboard/ignore', 'DashboardController@employeeCountReminderSessionIgnore');

    $router->get('employees/{id}/re-hire', 'EmployeesController@rehire');
    $router->get('employees/number', 'EmployeesController@number');
    $router->post('employees/number', 'EmployeesController@numberUpdate');
    $router->resource('employees', 'EmployeesController', ['as' => 'user']);

    /*
     * Other
     */
    $router->get('calculators', 'ToolsController@calculators');
    $router->get('faqs', 'ToolsController@faqs');
    $router->post('faqs/search', 'ToolsController@faqSearch');
    $router->get('permissions', [
        'as' => 'user.permissions',
        'uses' => 'PermissionController@index',
    ]);
    $router->post('permissions/submit', 'PermissionController@submit');
    $router->get('settings', [
        'as' => 'user.tools.settings',
        'uses' => 'ToolsController@settingsIndex',
    ]);
    $router->post('settings/submit', 'ToolsController@settingsSubmit');
});

Route::get('employees/attendance/delete/{id}', 'EmployeesController@deleteAttendanceRecord')->middleware('auth.admin');
