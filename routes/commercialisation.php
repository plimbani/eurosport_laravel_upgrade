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
    Route::get('v1/user/get-details/', '\Laraspace\Api\Controllers\Commercialisation\UserController@getDetails')->name("user.details");
    Route::post('v1/user/update/', '\Laraspace\Api\Controllers\Commercialisation\UserController@updateUser')->name("user.update");
    Route::post('v1/buy-license', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@buyLicense');    
    Route::get('v1/tournaments/list', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getList');
    Route::get('v1/get-tournament', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getTournament');
    Route::post('v1/manage-tournament', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@manageTournament');
    Route::post('v1/customer-tournament', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getTournamentByCustomer');
    Route::post('v1/customer-transactions', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@getCustomerTransactions');
    Route::post('v1/website-settings/save', '\Laraspace\Api\Controllers\Commercialisation\WebsiteSettingsController@saveSettings');
    Route::post('v1/admin/customer/update', '\Laraspace\Api\Controllers\Commercialisation\UserController@updateUserByAdmin');
    

    Route::post('v1/saveTournamentPricingDetail', '\Laraspace\Api\Controllers\Commercialisation\TournamentPricingController@saveTournamentPricingDetail');

    Route::get('v1/getUserTransactions', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getUserTransactions');

    Route::post('getSignedUrlForBuyLicensePrint', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@getSignedUrlForBuyLicensePrint');

    Route::post('v1/generateHashKey', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@generateHashKey');

    Route::post('v1/canManageTemplateSection', '\Laraspace\Api\Controllers\Commercialisation\TemplateController@canManageTemplateSection');
});

$api->version('v1', function($api) {
    $api->get('license/receipt/generate/{tournamentId}', 'Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@generatePaymentReceipt');
});

Route::get('v1/tournament-pricing-bands', '\Laraspace\Api\Controllers\Commercialisation\TournamentPricingController@getTournamentPricingBands');

//Payment response callback URL
Route::post('v1/payment/response', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@paymentResponse');

Route::get('v1/tournament-by-code', '\Laraspace\Api\Controllers\Commercialisation\TournamentController@getTournamentByCode');

Route::get('v1/get-website-settings', '\Laraspace\Api\Controllers\Commercialisation\WebsiteSettingsController@getSettings');

Route::get('v1/get-payment-status-messages', '\Laraspace\Api\Controllers\Commercialisation\BuyLicenseController@getPaymentStatusMessages');



