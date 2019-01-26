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

//Front-end Register API call
Route::post('/v1/commercialisation/thankyou', '\Laraspace\Api\Controllers\Commercialisation\RegisterController@register')->name("commerialisation.thankyou");
Route::get('/v1/country/list', '\Laraspace\Api\Controllers\CountryController@getList')->name("country.list");

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('v1/user/get-details/', '\Laraspace\Api\Controllers\UserController@getDetails')->name("user.details");
    Route::post('v1/user/update/', '\Laraspace\Api\Controllers\UserController@updateUser')->name("user.update");
    Route::post('v1/buy-license', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@buyLicense');    
    Route::get('v1/tournaments/list', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getList');
    Route::get('v1/get-tournament', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@tournamentSummary');
    Route::post('v1/tournaments/update', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@update');
});

Route::post('v1/generateHashKey', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@generateHashKey');

//Payment response callback URL
Route::post('v1/payment/response', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@paymentResponse');
Route::post('v1/generate/receipt', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@generatePaymentReceipt');




