<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function () {
    Route::post('login','AuthController@authenticate');
    Route::get('logout','AuthController@logout');
    Route::get('check','AuthController@check');
});

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', function ($api) {
    $api->get('users/test', 'Laraspace\Api\Controllers\EnvController@test2');

    // Team Stuff

    $api->get('teams', 'Laraspace\Api\Controllers\TeamController@getTeams');
    $api->post('team/create', 'Laraspace\Api\Controllers\TeamController@createTeam');
    $api->post('team/edit/{id}', 'Laraspace\Api\Controllers\TeamController@edit');
    $api->post('team/delete/{deleteid}', 'Laraspace\Api\Controllers\TeamController@deleteTeam');

    //Referee api
    $api->get('referees', 'Laraspace\Api\Controllers\RefereeController@getReferees');
    $api->post('referee/create', 'Laraspace\Api\Controllers\RefereeController@createReferee');
    $api->post('referee/edit/{id}', 'Laraspace\Api\Controllers\RefereeController@edit');
    $api->post('referee/delete/{deleteid}', 'Laraspace\Api\Controllers\RefereeController@deleteReferee');

    //MatchResult api
    $api->get('matches', 'Laraspace\Api\Controllers\MatchController@getMatches');
    $api->post('match/create', 'Laraspace\Api\Controllers\MatchController@createMatch');
    $api->post('match/edit/{id}', 'Laraspace\Api\Controllers\MatchController@edit');
    $api->post('match/delete/{deleteid}', 'Laraspace\Api\Controllers\MatchController@deleteMatch');

    //MatchResult api
    $api->get('pitches', 'Laraspace\Api\Controllers\PitchController@getPitches');
    $api->post('pitch/create', 'Laraspace\Api\Controllers\PitchController@createPitch');
    $api->post('pitch/edit/{id}', 'Laraspace\Api\Controllers\PitchController@edit');
    $api->post('pitch/delete/{deleteid}', 'Laraspace\Api\Controllers\PitchController@deletePitch');

    //Age Group Stuff

    $api->get('age_groups', 'Laraspace\Api\Controllers\AgeGroupController@getAgeGroups');
    $api->post('age_group/create', 'Laraspace\Api\Controllers\AgeGroupController@create');
    $api->post('age_group/edit/{id}', 'Laraspace\Api\Controllers\AgeGroupController@edit');
    $api->post('age_group/delete/{deleteid}', 'Laraspace\Api\Controllers\AgeGroupController@delete');

    //Tournament Api CRUD Routes
    $api->get('tournaments', 'Laraspace\Api\Controllers\TournamentController@index');
    $api->post('tournament/create', 'Laraspace\Api\Controllers\TournamentController@create');
    $api->post('tournament/edit/{id}', 'Laraspace\Api\Controllers\TournamentController@edit');
    $api->post('tournament/delete/{id}', 'Laraspace\Api\Controllers\TournamentController@delete');

    // User Stuff
    $api->get('users', 'Laraspace\Api\Controllers\UserController@getUsers');
    $api->post('user/create', 'Laraspace\Api\Controllers\UserController@createUser')->name('create.users');
    $api->post('user/edit/{id}', 'Laraspace\Api\Controllers\UserController@edit')->name('update.users');
    $api->post('user/delete/{deleteid}', 'Laraspace\Api\Controllers\UserController@deleteUser')->name('delete.users');
});


