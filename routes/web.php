<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::get('tournament/report/reportExport','\Laraspace\Api\Controllers\TournamentController@generateReport');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');
    
    
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail' )->name('password.email')->name('password.email');

Route::get('/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('home');

