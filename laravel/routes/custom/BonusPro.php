<?php

use Illuminate\Support\Facades\Route;

Route::model('plan', \App\BonusPro\Plan::class);

Route::middleware(['bonuspro', 'policy_updates', 'shared.inertia.user'])->group(function () {
    Route::post('/bonuspro/confirm-plan-password', 'BonusProController@confirmPlanPassword');
    Route::post('/bonuspro/confirm-and-delete', 'BonusProController@confirmAndDelete');
    Route::get('/bonuspro/{plan}/employees', 'BonusProController@employees')
        ->where('plan', '[0-9]+')
        ->middleware('can:view,plan');
    Route::post('bonuspro/save-plan-data', 'BonusProController@savePlanData');
    Route::post('bonuspro/save-initial-setup', 'BonusProController@saveInitialSetup');
    Route::post('bonuspro/save-initial-setup-draft', 'BonusProController@saveInitialSetupDraft');
    Route::post('bonuspro/add-employee', 'BonusProController@addEmployee');
    Route::post('bonuspro/create-employee', 'BonusProController@createEmployee');
    Route::delete('bonuspro/remove-employee', 'BonusProController@removeEmployee');
    Route::post('bonuspro/save-fund', 'BonusProController@saveFund');
    Route::post('bonuspro/save-month-data', 'BonusProController@saveMonthData');
    Route::post('bonuspro/update-fund', 'BonusProController@updateFund');
    Route::delete('bonuspro/remove-fund', 'BonusProController@removeFund');
    Route::post('bonuspro/save-bonus-percentage', 'BonusProController@saveBonusPercentage');
    Route::post('bonuspro/save-bonus-percentage-draft', 'BonusProController@saveBonusPercentageDraft');
    Route::post('bonuspro/generate-report', 'BonusProController@generateReport');
    Route::get('bonuspro/{id}/resume', 'BonusProController@resume')->name('bonuspro.resume');
    Route::resource('bonuspro', 'BonusProController');

    Route::group(['prefix' => 'bonuspro/auth'], function () {
        Route::get('{plan}/login', 'BonusProPlanLoginController@showLoginForm')->name('bonuspro.plan.showLogin');
        Route::post('{plan}/login', 'BonusProPlanLoginController@login')->name('bonuspro.plan.login');
        Route::get('{plan}/logout', 'BonusProPlanLoginController@logout')->name('bonuspro.plan.logout');
    });

    Route::group(['prefix' => 'bonuspro/password'], function () {
        Route::get('{plan}/email', 'BonusProPasswordController@showLinkRequestForm')->name('bonuspro.plan.showLinkReset');
        Route::post('{plan}/email', 'BonusProPasswordController@sendResetLinkEmail')->name('bonuspro.plan.sendLink');
        Route::get('{plan}/reset/{token}', 'BonusProPlanResetPasswordController@showResetForm')->name('bonuspro.plan.showReset');
        Route::post('{plan}/reset', 'BonusProPlanResetPasswordController@reset')->name('bonuspro.plan.reset');
    });
});
