<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['domain' => config('app.domain')], function() {
	Route::get('tournament/report/reportExport','\Laraspace\Api\Controllers\TournamentController@generateReport');
	Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

	Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');
	Route::get('mlogin','Auth\ResetPasswordController@userMlogin');

	Route::get('user/setpassword/{key}','\Laraspace\Api\Controllers\UserController@setPassword');

	Route::get('pdf/footer', 'PDFController@getFooter')->name('pdf.footer');

	Route::get('/{vue?}', function () {
		return view('app');
	})->where('vue', '[\/\w\.-]*')->name('home');

	Route::post('/passwordactivate', [
		'as' => 'password', 'uses' => '\Laraspace\Api\Controllers\UserController@passwordActivate'
	]);
});

Route::group(['domain' => '{domain}', 'middleware' => ['verify.website'], 'namespace' => 'Frontend'], function() {
	Route::get('/', 'HomeController@getHomePageDetails')->name('home.page.details');
	Route::get('/teams', 'WebsiteTeamController@getTeamPageDetails')->name('team.page.front.details');
	Route::get('/matches', 'MatchController@getMatchPageDetails')->name('match.page.front.details');
	Route::get('/venue', 'WebsiteVenueController@getVenuePageDetails')->name('venue.page.front.details');
	Route::get('/tournament', 'WebsiteTournamentController@getTournamentPageDetails')->name('tournament.page.front.details');
	Route::get('/rules', 'WebsiteTournamentController@getRulesPageDetails')->name('rules.page.front.details');
	Route::get('/history', 'WebsiteTournamentController@getHistoryPageDetails')->name('history.page.front.details');
	Route::get('/program', 'ProgramController@getProgramPageDetails')->name('program.page.front.details');
	Route::get('/program/{additionalPage}', 'ProgramController@getAdditionalProgramPageDetails')->name('additional.program.page.front.details');
	Route::get('/stay', 'StayController@getStayPageDetails')->name('stay.page.front.details');
	Route::get('/meals', 'StayController@getMealsPageDetails')->name('meals.page.front.details');
	Route::get('/accommodation', 'StayController@getAccommodationPageDetails')->name('accommodation.page.front.details');
	Route::get('/stay/{additionalPage}', 'StayController@getAdditionalStayPageDetails')->name('additional.stay.page.front.details');
	Route::get('/visitors', 'VisitorController@getVisitorPageDetails')->name('visitor.page.front.details');
	Route::get('/tourist-information', 'VisitorController@getTouristPageDetails')->name('tourist.page.front.details');
	Route::get('/media', 'MediaController@getMediaPageDetails')->name('media.page.front.details');
	Route::get('/contact', 'ContactController@getMediaPageDetails')->name('media.page.front.details');
});
