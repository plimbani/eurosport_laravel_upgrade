<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::get('auth/{provider}/callback', '\Laraspace\Http\Controllers\Auth\LoginController@handleProviderCallback')->where('provider', 'facebook');

Route::group(['domain' => config('app.domain')], function() {
	Route::get('tournament/report/reportExport','\Laraspace\Api\Controllers\TournamentController@generateReport');
	//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

	Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');
	//Route::get('mlogin','Auth\ResetPasswordController@userMlogin');

	Route::get('pdf/footer', 'PDFController@getFooter')->name('pdf.footer');

	Route::get('getMatchSchedulePdfFooter', 'PDFController@getMatchSchedulePdfFooter')->name('match.schedule.pdf.footer');

	Route::get('pdf/matchgraphic', 'PDFController@matchgraphic')->name('pdf.matchgraphic');

	Route::get('/{vue?}', function () {
		return view('app');
	})->where('vue', '[\/\w\.-]*')->name('home');

	Route::post('/passwordactivate', [
		'as' => 'password', 'uses' => '\Laraspace\Api\Controllers\UserController@passwordActivate'
	]);
});

Route::group(['domain' => '{domain}', 'middleware' => ['verify.website', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'], 'prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Frontend'], function() {
	Route::get('/', 'HomeController@getHomePageDetails')->name('home.page.details');
	Route::get('/teams', 'WebsiteTeamController@getTeamPageDetails')->name('team.page.details');
	Route::get('/matches', 'MatchController@getMatchPageDetails')->name('match.page.details');
	Route::get('/venue', 'WebsiteVenueController@getVenuePageDetails')->name('venue.page.details');
	Route::get('/tournament', 'WebsiteTournamentController@getTournamentPageDetails')->name('tournament.page.details');
	Route::get('/rules', 'WebsiteTournamentController@getRulesPageDetails')->name('rules.page.details');
	Route::get('/history', 'WebsiteTournamentController@getHistoryPageDetails')->name('history.page.details');
	Route::get('/tournament/{additionalPageName}', 'WebsiteTournamentController@getAdditionalTournamentPageDetails')->name('additional.tournament.page.details');
	Route::get('/program', 'ProgramController@getProgramPageDetails')->name('program.page.details');
	Route::get('/program/{additionalPageName}', 'ProgramController@getAdditionalProgramPageDetails')->name('additional.program.page.details');
	Route::get('/stay', 'StayController@getStayPageDetails')->name('stay.page.details');
	Route::get('/meals', 'StayController@getMealsPageDetails')->name('meals.page.details');
	Route::get('/accommodation', 'StayController@getAccommodationPageDetails')->name('accommodation.page.details');
	Route::get('/stay/{additionalPageName}', 'StayController@getAdditionalStayPageDetails')->name('additional.stay.page.details');
	Route::get('/visitors', 'VisitorController@getVisitorPageDetails')->name('visitor.page.details');
	Route::get('/tourist-information', 'VisitorController@getTouristPageDetails')->name('tourist.page.details');
	Route::get('/media', 'MediaController@getMediaPageDetails')->name('media.page.details');
	Route::get('/contact', 'ContactController@getContactPageDetails')->name('contact.page.details');
	Route::post('/submitInquiry', 'ContactController@submitInquiry')->name('submit.enquiry');

	Route::get('/tips', 'VisitorController@getTipsPageDetails')->name('tips.page.details');
	Route::get('/public-transport', 'VisitorController@getPublicTransportPageDetails')->name('public.transport.page.details');
});
