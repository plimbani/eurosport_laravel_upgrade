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

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api')->name('api.user');
*/

// Api Stuff
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    //$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
    $api->get('users/test', 'App\Api\Controllers\EnvController@test2');
    $api->get('teams', 'App\Api\Controllers\TeamController@getTeams');
    $api->post('team/create', 'App\Api\Controllers\TeamController@createTeam');
});
