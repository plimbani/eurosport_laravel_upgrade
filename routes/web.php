<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('dashboard');
})->name('index');*/

Route::get('/', 'HomeController@index');
Route::resource('home', 'HomeController');

Route::get('/teams', 'TeamController@index');
Route::get('/team/create', 'TeamController@create');

Auth::routes();

    Route::get('/user/verification/{token}', 'Auth\VerifyAccountController@userActivation')->name('user.verification');
    Route::get('/user/verification/resend/{user}', 'Auth\VerifyAccountController@sendConfirmationEmail')
                ->name('user.verification.resend');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/test2', 'HomeController@getUsers');

    //Route::get('/teams', 'TeamController@index');
    //Route::get('/team/create', 'TeamController@create');

    Route::get('/referees', 'RefereeController@index');
    Route::get('/referee/create', 'RefereeController@create');
    Route::any('/referee/delete/{delete_id}', 'RefereeController@deleteReferee');

    Route::get('/matches', 'MatchController@index');
    Route::get('/match/create', 'MatchController@create');
    Route::any('/match/delete/{delete_id}', 'MatchController@deleteMatch');

    Route::get('/pitches', 'PitchController@index');
    Route::get('/pitch/create', 'PitchController@create');
    Route::post('/pitch/store', 'PitchController@store');
    Route::get('/pitch/getdata', 'PitchController@pitchAllocation');

    // Route::any('/referee/delete/{delete_id}', 'RefereeController@deleteReferee');
