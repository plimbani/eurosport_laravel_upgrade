<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

// Route::get('/{vue?}', function () {
//     return view('app');
// })->where('vue', '[\/\w\.-]*')->name('home');

Route::get('/tournaments', 'TournamentController@index');
Route::get('/tournament/create', 'TournamentController@create');
Route::POST('/tournament/store', 'TournamentController@store');

Route::get('/tournament/{tournament_id}', 'TournamentController@dashboard');

Auth::routes();

Route::get('/user/verification/{token}', 'Auth\VerifyAccountController@userActivation')->name('user.verification');
Route::get('/user/verification/resend/{user}', 'Auth\VerifyAccountController@sendConfirmationEmail')
            ->name('user.verification.resend');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/test2', 'HomeController@getUsers');

Route::get('/teams', 'TeamController@index');
Route::get('/team/create', 'TeamController@create');
Route::get('/team/edit/{edit_id}', 'TeamController@edit');

Route::get('/referees', 'RefereeController@index');
Route::get('/referee/create', 'RefereeController@create');
Route::get('/referee/edit/{edit_id}', 'RefereeController@edit');
Route::any('/referee/delete/{delete_id}', 'RefereeController@deleteReferee');

Route::get('/matches', 'MatchController@index');
Route::get('/match/create', 'MatchController@create');
Route::get('/match/edit/{edit_id}', 'MatchController@edit');
Route::any('/match/delete/{delete_id}', 'MatchController@deleteMatch');

Route::get('/pitches', 'PitchController@index');
Route::get('/pitch/create', 'PitchController@create');
Route::post('/pitch/store', 'PitchController@store');
Route::get('/pitch/getdata', 'PitchController@pitchAllocation');
