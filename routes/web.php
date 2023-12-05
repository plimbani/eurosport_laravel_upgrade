<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::get('auth/{provider}/callback', '\App\Http\Controllers\Auth\LoginController@handleProviderCallback')->where('provider', 'facebook');

Route::group(['domain' => config('app.domain')], function () {
    Route::get('tournament/report/reportExport', '\App\Api\Controllers\TournamentController@generateReport');
    //Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');
    //Route::get('mlogin','Auth\ResetPasswordController@userMlogin');

    Route::get('pdf/footer', 'PDFController@getFooter')->name('pdf.footer');

    Route::get('getMatchSchedulePdfFooter', 'PDFController@getMatchSchedulePdfFooter')->name('match.schedule.pdf.footer');

    Route::get('pdf/matchgraphic', 'PDFController@matchgraphic')->name('pdf.matchgraphic');

    Route::get('/admin/show-presentation/{tournamentslug}', '\App\Http\Controllers\PresentationController@showPresentation')->name('presentation.show');

    Route::get('/{vue?}', function () {
        return view('app');
    })->where('vue', '[\/\w\.-]*')->name('home');

    Route::post('/passwordactivate', [
        'as' => 'password', 'uses' => '\App\Api\Controllers\UserController@passwordActivate',
    ]);
});
