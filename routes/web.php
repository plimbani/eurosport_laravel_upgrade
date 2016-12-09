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

Route::get('/', function () {
    return view('welcome');
})->name('index');

Auth::routes();

Route::get('/user/verification/{token}', 'Auth\VerifyAccountController@userActivation')->name('user.verification');
Route::get('/user/verification/resend/{user}', 'Auth\VerifyAccountController@sendConfirmationEmail')
            ->name('user.verification.resend');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/test2', 'HomeController@getUsers');

Route::get('/teams', 'TeamController@index');
Route::get('/team/create', 'TeamController@create');
