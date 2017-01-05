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

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api')->name('api.user');
*/

// Api Stuff

Route::post('login', 'Auth\LoginController@login');

Route::group([
    'prefix' => 'restricted',
    'middleware' => 'auth:api',
], function () {

    // Authentication Routes...
    Route::get('logout', 'Auth\LoginController@logout');

    Route::get('/test', function () {
        return 'authenticated';
    });
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('users/test', 'App\Api\Controllers\EnvController@test2');

    // Team Stuff

    //$api->get('teams', 'App\Api\Controllers\TeamController@getTeams');
    //$api->post('team/create', 'App\Api\Controllers\TeamController@createTeam');
    //$api->post('team/edit/{id}', 'App\Api\Controllers\TeamController@edit');
    //$api->post('team/delete/{deleteid}', 'App\Api\Controllers\TeamController@deleteTeam');

    //Referee api
    $api->get('referees', 'App\Api\Controllers\RefereeController@getReferees');
    $api->post('referee/create', 'App\Api\Controllers\RefereeController@createReferee');
    $api->post('referee/edit/{id}', 'App\Api\Controllers\RefereeController@edit');
    $api->post('referee/delete/{deleteid}', 'App\Api\Controllers\RefereeController@deleteReferee');

    //MatchResult api
    $api->get('matches', 'App\Api\Controllers\MatchController@getMatches');
    $api->post('match/create', 'App\Api\Controllers\MatchController@createMatch');
    $api->post('match/edit/{id}', 'App\Api\Controllers\MatchController@edit');
    $api->post('match/delete/{deleteid}', 'App\Api\Controllers\MatchController@deleteMatch');

    //Age Group Stuff

    $api->get('age_groups', 'App\Api\Controllers\AgeGroupController@getAgeGroups');
    $api->post('age_group/create', 'App\Api\Controllers\AgeGroupController@create');
    $api->post('age_group/edit/{id}', 'App\Api\Controllers\AgeGroupController@edit');
    $api->post('age_group/delete/{deleteid}', 'App\Api\Controllers\AgeGroupController@delete');

    //Tournament Api CRUD Routes
    $api->get('tournaments', 'App\Api\Controllers\TournamentController@index');
    $api->post('tournament/create', 'App\Api\Controllers\TournamentController@create');
    $api->post('tournament/edit/{id}', 'App\Api\Controllers\TournamentController@edit');
    $api->post('tournament/delete/{id}', 'App\Api\Controllers\TournamentController@delete');
});
