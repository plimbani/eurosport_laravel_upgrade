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
    Route::post('login', 'AuthController@authenticate');
    Route::get('logout', 'AuthController@logout');
    Route::post('check', 'AuthController@check');

    // Social logins
    Route::post('social/login', 'AuthController@socialLogin');

    //check token validate
    Route::get('token_validate', 'AuthController@token_validate');
});

Route::get('password/reset/{token}', '\App\Api\Controllers\PasswordController@getReset');
Route::post('password/reset', '\App\Api\Controllers\PasswordController@postReset');
//Route::post('password/email', 'App\Api\Controllers\PasswordController@postEmail');
Route::get('/mlogin', '\App\Http\Controllers\Auth\ResetPasswordController@userMlogin');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'signedurl'], function ($api) {
    $api->get('tournament/report/print', 'App\Api\Controllers\TournamentController@generatePrint');
    $api->get('match/print', 'App\Api\Controllers\MatchController@generateMatchPrint');
    $api->get('match/reportCard/{refereeId}', 'App\Api\Controllers\MatchController@generateRefereeReportCard');

    $api->get('teams/getTeamsFairPlayData/report/print', 'App\Api\Controllers\TeamController@printTeamFairPlayReport');
    $api->get('pitchPlanner/print/{tournamentId}', 'App\Api\Controllers\PitchController@generatePitchPlannerPrint');

    $api->get('generateTemplateGraphic/{ageCategoryId}', 'App\Api\Controllers\TemplateController@generateTemplateGraphic');
    $api->get('match/schedule/print', 'App\Api\Controllers\AgeGroupController@generateMatchSchedulePrint');
});

$api->version('v1', function ($api) {
    // Need to check later
    $api->get('pitch/reportCard/{pitchId}', 'App\Api\Controllers\PitchController@generatePitchMatchReport')->name('pitch.reportcard');
    $api->get('teams/getGroupsViewData/report/print', 'App\Api\Controllers\TeamController@printGroupsViewReport');
    $api->get('pitchPlanner/export/{tournamentId}', 'App\Api\Controllers\PitchController@generatePitchPlannerExport');
    $api->get('referee/downloadSampleUploadSheet', 'App\Api\Controllers\RefereeController@downloadSampleUploadSheet');
    $api->get('match/downloadSampleUploadSheet', 'App\Api\Controllers\MatchController@downloadSampleUploadSheet');
    $api->get('match/report/generate/{ageGroupId}/{tournamentId}',
        'App\Api\Controllers\MatchController@generateCategoryReport')->name('generate.category.report');
    $api->get('tournament/report/reportExport', 'App\Api\Controllers\TournamentController@exportReport');
    $api->get('tournament/report/reportDownloadAllTeam', 'App\Api\Controllers\TournamentController@downloadReportAllTeam');
    $api->get('teams/getTeamsFairPlayData/report/reportExport', 'App\Api\Controllers\TeamController@exportTeamFairPlayReport');
    $api->get('users/getUserTableData', 'App\Api\Controllers\UserController@getUserTableData');

    $api->post('tournaments/getTournamentByStatus', 'App\Api\Controllers\TournamentController@getTournamentByStatus');
    $api->get('tournaments/getTournamentBySlug/{slug}', 'App\Api\Controllers\TournamentController@getTournamentBySlug');

    $api->post('tournament/getCategoryCompetitions', 'App\Api\Controllers\TournamentController@getCategoryCompetitions');
    $api->post('match/getFixtures', 'App\Api\Controllers\MatchController@getFixtures');
    $api->post('match/getDrawTable', 'App\Api\Controllers\MatchController@getDrawTable');
    $api->post('teams/getAllCompetitionTeamsFromFixture', 'App\Api\Controllers\TeamController@getAllCompetitionTeamsFromFixture');

    $api->post('match/refreshStanding', 'App\Api\Controllers\MatchController@refreshStanding');
    $api->post('tournament/getDropDownData', 'App\Api\Controllers\TournamentController@tournamentFilter');
    $api->post('teams/teamsTournament',
        'App\Api\Controllers\TeamController@getAllTournamentTeams');
    $api->post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@resetlink');

    $api->post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.request');
    // $api->get('mlogin', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.request');
    $api->get('/mlogin', '\App\Http\Controllers\Auth\ResetPasswordController@userMlogin');

    $api->get('match/automateMatchScheduleAndResult/{tournamentId?}/{ageGroupId?}', 'App\Api\Controllers\MatchController@automateMatchScheduleAndResult')->name('automate.match.result');

    $api->post('appversion', 'App\Api\Controllers\VersionController@apkVersion');

    $api->post('get_project_configurations', 'App\Api\Controllers\ProjectConfigurationController@getProjectConfigurations');

    $api->post('age_group/getCompetationFormat',
        'App\Api\Controllers\AgeGroupController@getCompetationFormat');

    $api->post('match/getDraws', 'App\Api\Controllers\MatchController@getDraws');

    $api->post('match/getStanding/{refreshStanding?}',
        'App\Api\Controllers\MatchController@getStanding');

    $api->post('user/create', 'App\Api\Controllers\UserController@createUser')->name('create.users');

    $api->post('/passwordactivate', '\App\Api\Controllers\UserController@passwordActivate');
    $api->get('tournaments', 'App\Api\Controllers\TournamentController@index');
    $api->get('tournaments-years', 'App\Api\Controllers\TournamentController@tournamentYears');

    $api->post('age_group/getPlacingsData', 'App\Api\Controllers\AgeGroupController@getPlacingsData');

    $api->post('tournament/getCompetitionAndPitchDetail', 'App\Api\Controllers\TournamentController@getCompetitionAndPitchDetail');

    $api->post('tournament/scheduleAutomaticPitchPlanning', 'App\Api\Controllers\TournamentController@scheduleAutomaticPitchPlanning');
    $api->get('/changeWebsiteMenus', 'App\Api\Controllers\HomeController@changeWebsiteMenus');

    $api->post('/getAllPitchesWithDays/{pitchId}', 'App\Api\Controllers\TournamentController@getAllPitchesWithDays');

    $api->post('tournament/updateCompetitionDisplayName', 'App\Api\Controllers\TournamentController@updateCompetitionDisplayName');

    $api->get('getCountries', 'App\Api\Controllers\UserController@getAllCountries');
    $api->get('getAllLanguages', 'App\Api\Controllers\UserController@getAllLanguages');
    $api->post('tournament/updateCategoryDivisionName', 'App\Api\Controllers\TournamentController@updateCategoryDivisionName');
    $api->post('/userResendEmail', '\App\Api\Controllers\UserController@userResendEmail');
    $api->get('user/setpasswordCheck/{key}', '\App\Api\Controllers\UserController@setPassword');

    $api->post('deleteFinalPlacingTeam', 'App\Api\Controllers\AgeGroupController@deleteFinalPlacingTeam');

    $api->post('getTemplateGraphic', 'App\Api\Controllers\TemplateController@getTemplateGraphic');
    $api->post('getTemplateGraphicOfLeague', 'App\Api\Controllers\TemplateController@getTemplateGraphicOfLeague');

    $api->post('getSignedUrlForMatchSchedulePrint', 'App\Api\Controllers\AgeGroupController@getSignedUrlForMatchSchedulePrint');
});

$api->version('v1', ['middleware' => 'jwt.auth'], function ($api) {
    // for localization
    $locale = \Request::header('locale');

    if ($locale != '') {
        App::setLocale($locale);
    }

    $api->post('teams', 'App\Api\Controllers\TeamController@getTeams');
    $api->post('team/create', 'App\Api\Controllers\TeamController@createTeam');

    $api->post('team/group/assign', 'App\Api\Controllers\TeamController@assignTeam');
    $api->post('team/getClubsTeams', 'App\Api\Controllers\TeamController@getClubTeams');

    $api->post('team/checkTeamExist', 'App\Api\Controllers\TeamController@checkTeamExist');

    $api->post('editTeamDetails/{id}', 'App\Api\Controllers\TeamController@editTeamDetails');
    $api->get('getAllTeamColors', 'App\Api\Controllers\TeamController@getAllTeamColors');
    $api->get('getAllCountries', 'App\Api\Controllers\TeamController@getAllCountries');
    $api->get('getAllClubs', 'App\Api\Controllers\TeamController@getAllClubs');

    $api->post('getClubsByTournamentId/{tournamentId}', 'App\Api\Controllers\TeamController@getClubsByTournamentId');

    $api->post('updateTeamDetails/{id}', 'App\Api\Controllers\TeamController@updateTeamDetails');
    $api->post('resetAllTeams', 'App\Api\Controllers\TeamController@resetAllTeams');
    // Method for get All TournamentTeams

    // Manually change team name
    $api->post('teams/changeTeamName',
        'App\Api\Controllers\TeamController@changeTeamName');

    //MatchResult api
    $api->post('match/schedule', 'App\Api\Controllers\MatchController@scheduleMatch');
    $api->post('match/unschedule', 'App\Api\Controllers\MatchController@unscheduleMatch');
    $api->post('match/fixtureUnschedule', 'App\Api\Controllers\MatchController@matchUnscheduledFixtures');
    $api->post('match/unscheduleFixturesByAgeCategory', 'App\Api\Controllers\MatchController@unscheduleFixturesByAgeCategory');
    $api->post('match/getAgeCategoriesToUnscheduleFixtures', 'App\Api\Controllers\MatchController@getAgeCategoriesToUnscheduleFixtures');
    $api->post('match/unscheduleAllFixtures', 'App\Api\Controllers\MatchController@unscheduleAllFixtures');
    $api->post('saveScheduleMatches', 'App\Api\Controllers\MatchController@saveScheduleMatches');
    $api->post('match/getScheduledMatch', 'App\Api\Controllers\MatchController@getScheduledMatch');

    $api->post('match/detail', 'App\Api\Controllers\MatchController@getMatchDetail');
    $api->post('match/removeAssignedReferee', 'App\Api\Controllers\MatchController@removeAssignedReferee');
    $api->post('match/assignReferee', 'App\Api\Controllers\MatchController@assignReferee');
    $api->post('match/saveResult', 'App\Api\Controllers\MatchController@saveResult');
    $api->post('match/saveAllResults', 'App\Api\Controllers\MatchController@saveAllResults');

    $api->post('match/getUnavailableBlock', 'App\Api\Controllers\MatchController@getUnavailableBlock');
    $api->post('match/updateScore', 'App\Api\Controllers\MatchController@updateScore');

    $api->post('match/checkTeamIntervalforMatches',
        'App\Api\Controllers\MatchController@checkTeamIntervalforMatches');

    $api->post('referees', 'App\Api\Controllers\RefereeController@getReferees');
    $api->post('referee/create', 'App\Api\Controllers\RefereeController@createReferee');
    $api->post('referee/update', 'App\Api\Controllers\RefereeController@updateReferee');

    $api->post('referee/refereeDetail', 'App\Api\Controllers\RefereeController@refereeDetail');
    $api->post('referee/delete/{deleteid}', 'App\Api\Controllers\RefereeController@deleteReferee');

    $api->post('referee/uploadExcel', 'App\Api\Controllers\RefereeController@uploadRefereesExcel');

    $api->get('pitches/{tournamentId}', 'App\Api\Controllers\PitchController@getPitches');
    $api->get('getPitchSizeWiseSummary/{tournamentId}', 'App\Api\Controllers\PitchController@getPitchSizeWiseSummary');
    $api->get('getLocationWiseSummary/{tournamentId}', 'App\Api\Controllers\PitchController@getLocationWiseSummary');
    $api->get('pitch/show/{pitchId}', 'App\Api\Controllers\PitchController@show');
    $api->post('pitch/create', 'App\Api\Controllers\PitchController@createPitch');
    $api->post('pitch/edit/{id}', 'App\Api\Controllers\PitchController@edit');
    $api->post('pitch/delete/{deleteid}', 'App\Api\Controllers\PitchController@deletePitch');
    $api->post('pitch/updatePitchOrder', 'App\Api\Controllers\PitchController@updatePitchOrder');

    $api->post('age_group/createCompetationFomat', 'App\Api\Controllers\AgeGroupController@createCompetationFomat');
    $api->post('age_group/deleteCompetationFormat', 'App\Api\Controllers\AgeGroupController@deleteCompetationFormat');

    $api->get('venues/getAll/{tournamentId}', 'App\Api\Controllers\VenueController@getVenues');

    $api->post('tournament/updateStatus', 'App\Api\Controllers\TournamentController@updateStatus');

    $api->post('tournaments/templates', 'App\Api\Controllers\TournamentController@templates');

    $api->post('tournaments/getTemplate', 'App\Api\Controllers\TournamentController@getTemplate');

    $api->post('tournament/create', 'App\Api\Controllers\TournamentController@create');
    $api->post('tournament/delete/{id}', 'App\Api\Controllers\TournamentController@delete');
    $api->post('tournaments/tournamentSummary', 'App\Api\Controllers\TournamentController@tournamentSummary');

    $api->post('tournament/details/add',
        'App\Api\Controllers\TournamentController@addTournamentDetails');

    $api->get('users', 'App\Api\Controllers\UserController@getUsers');
    $api->get('users1', function () {
    });

    $api->post('users/getUsersByRegisterType',
        'App\Api\Controllers\UserController@getUsersByRegisterType');

    $api->get('user/edit/{id}', 'App\Api\Controllers\UserController@edit')->name('edit.users');
    $api->post('user/getDetails', 'App\Api\Controllers\UserController@getUserDetails');

    $api->post('user/update/{id}', 'App\Api\Controllers\UserController@update')->name('update.users');
    $api->post('user/delete/{id}', 'App\Api\Controllers\UserController@deleteUser')->name('delete.users');

    $api->post('user/status', 'App\Api\Controllers\UserController@changeUserStatus');

    $api->post('users/updatefcm', 'App\Api\Controllers\UserController@updatefcm');

    $api->post('users/sendNotification',
        'App\Api\Controllers\PushMessagesController@sendNotification');
    $api->post('users/getMessage', 'App\Api\Controllers\PushMessagesController@getMessages');

    $api->post('/tournament/saveSettings', '\App\Api\Controllers\TournamentController@saveSettings');
    $api->post('/tournament/saveContactDetails', '\App\Api\Controllers\TournamentController@saveContactDetails');
    $api->post('/tournament/saveVenueDetails', '\App\Api\Controllers\TournamentController@saveVenueDetails');
    $api->get('/tournament/getPresentationSettings/{tournamentId}', '\App\Api\Controllers\TournamentController@getPresentationSettings');

    $api->post('/user/resendEmail', '\App\Api\Controllers\UserController@resendEmail');

    $api->get('roles', 'App\Api\Controllers\RoleController@getRoles');

    $api->get('tournament/report/generate',
        'App\Api\Controllers\TournamentController@generateReport');

    $api->post('users/setFavourite', 'App\Api\Controllers\UserController@setFavourite');
    $api->post('users/setFavouriteTeam', 'App\Api\Controllers\UserController@setFavouriteTeam');
    $api->post('users/removeFavourite', 'App\Api\Controllers\UserController@removeFavourite');
    $api->post('users/removeFavouriteTeam', 'App\Api\Controllers\UserController@removeFavouriteTeam');
    $api->post('users/setDefaultFavourite', 'App\Api\Controllers\UserController@setDefaultFavourite');

    $api->post('users/getLoginUserFavouriteTournament',
        'App\Api\Controllers\TournamentController@getUserLoginFavouriteTournament');
    $api->post('tournaments/getTournamentClub',
        'App\Api\Controllers\TournamentController@getTournamentClub');

    $api->post('teams/getTeamsList', 'App\Api\Controllers\TeamController@getTeamsList');
    $api->post('teams/getAllTournamentTeams', 'App\Api\Controllers\TeamController@getAllTournamentTeams');

    $api->post('users/postSetting', 'App\Api\Controllers\UserController@postSetting');
    $api->post('users/getSetting', 'App\Api\Controllers\UserController@getSetting');

    $api->post('match/saveStandingsManually', 'App\Api\Controllers\MatchController@saveStandingsManually');

    $api->post('user/changeTournamentPermission',
        'App\Api\Controllers\UserController@changeTournamentPermission');
    $api->get('user/getUserTournaments/{id}', 'App\Api\Controllers\UserController@getUserTournaments');

    $api->post('tournament/saveCategoryCompetitionColor',
        'App\Api\Controllers\TournamentController@saveCategoryCompetitionColor');

    // routes for signed url
    $api->post('getSignedUrlForMatchReport', 'App\Api\Controllers\MatchController@getSignedUrlForMatchReport');

    $api->post('getSignedUrlForTournamentReport', 'App\Api\Controllers\TournamentController@getSignedUrlForTournamentReport');

    $api->post('getSignedUrlForMatchPrint', 'App\Api\Controllers\MatchController@getSignedUrlForMatchPrint');

    $api->post('getSignedUrlForRefereeReport/{refereeId}', 'App\Api\Controllers\MatchController@getSignedUrlForRefereeReport');

    $api->post('getSignedUrlForPitchMatchReport/{pitchId}', 'App\Api\Controllers\PitchController@getSignedUrlForPitchMatchReport');

    $api->post('getSignedUrlForTournamentReportExport', 'App\Api\Controllers\TournamentController@getSignedUrlForTournamentReportExport');

    $api->post('getSignedUrlForUsersTableData', 'App\Api\Controllers\UserController@getSignedUrlForUsersTableData');

    $api->get('getTeamsFairPlayData', 'App\Api\Controllers\TeamController@getTeamsFairPlayData');
    $api->post('getSignedUrlForTeamsFairPlayReportExport', 'App\Api\Controllers\TeamController@getSignedUrlForTeamsFairPlayReportExport');

    $api->post('allocateTeamsAutomatically', 'App\Api\Controllers\TeamController@allocateTeamsAutomatically');

    $api->post('getSignedUrlForFairPlayReportPrint', 'App\Api\Controllers\TeamController@getSignedUrlForFairPlayReportPrint');

    $api->post('match/saveUnavailableBlock', 'App\Api\Controllers\MatchController@saveUnavailableBlock');
    $api->post('match/remove_block/{blockId}', 'App\Api\Controllers\MatchController@removeBlock');
    $api->post('getSignedUrlForPitchPlannerPrint/{tournamentId}', 'App\Api\Controllers\PitchController@getSignedUrlForPitchPlannerPrint');
    $api->post('getSignedUrlForPitchPlannerExport/{tournamentId}', 'App\Api\Controllers\PitchController@getSignedUrlForPitchPlannerExport');

    $api->post('getSignedUrlForRefereeSampleDownload', 'App\Api\Controllers\RefereeController@getSignedUrlForRefereeSampleDownload');
    $api->post('getSignedUrlForTeamsSpreadsheetSampleDownload', 'App\Api\Controllers\MatchController@getSignedUrlForTeamsSpreadsheetSampleDownload');

    $api->post('getTemplates', 'App\Api\Controllers\TemplateController@getTemplates');
    $api->post('getTemplateDetail', 'App\Api\Controllers\TemplateController@getTemplateDetail');
    $api->get('templates/getUsersForFilter', 'App\Api\Controllers\TemplateController@getUsersForFilter');
    $api->post('template/delete/{id}', 'App\Api\Controllers\TemplateController@deleteTemplate');
    $api->get('template/edit/{id}', 'App\Api\Controllers\TemplateController@editTemplate');
    $api->post('saveTemplateDetail', 'App\Api\Controllers\TemplateController@saveTemplateDetail');
    $api->post('updateTemplateDetail', 'App\Api\Controllers\TemplateController@updateTemplateDetail');

    $api->post('age_group/copyAgeCategory', 'App\Api\Controllers\AgeGroupController@copyAgeCategory');
    $api->post('viewGraphicImage', 'App\Api\Controllers\AgeGroupController@viewTemplateGraphicImage');

    $api->post('duplicateTournament', 'App\Api\Controllers\TournamentController@duplicateTournament');

    $api->post('duplicateTournamentList', 'App\Api\Controllers\TournamentController@duplicateTournamentList');

    $api->post('updateAppDeviceVersion', 'App\Api\Controllers\UserController@updateAppDeviceVersion');

    $api->post('getTournamentTeamDetails', 'App\Api\Controllers\TeamController@getTournamentTeamDetails');

    $api->post('pitchSearchRecord', 'App\Api\Controllers\PitchController@getPitchSearchRecord');

    $api->post('getVenuesDropDownData', 'App\Api\Controllers\PitchController@getVenuesDropDownData');

    $api->post('getSignedUrlForGroupsViewReport', 'App\Api\Controllers\TeamController@getSignedUrlForGroupsViewReport');

    $api->post('user/validateemail', 'App\Api\Controllers\UserController@validateUserEmail');

    $api->post('user/verifyResultAdminUser', 'App\Api\Controllers\UserController@verifyResultAdminUser');

    $api->get('getMatchesAndStandingsOfAgeCategory/{ageCategoryId}', 'App\Http\Controllers\PresentationController@getMatchesAndStandingsOfAgeCategory');
});

// Websites CMS routes
$api->version('v1', ['middleware' => 'jwt.auth'], function ($api) {
    // Published tournaments
    $api->get('getAllPublishedTournaments', 'App\Api\Controllers\TournamentController@getAllPublishedTournaments');

    // Website & Tournament permissions
    $api->post('user/changePermissions', 'App\Api\Controllers\UserController@changePermissions');

    // Image path
    $api->get('getConfigurationDetail', 'App\Api\Controllers\TournamentController@getConfigurationDetail');

    $api->post('uploadImage', 'App\Api\Controllers\UploadMediaController@uploadImage');

});

// Website frontend API calls
$api->version('v1', function ($api) {
    $api->get('getWebsiteMessages/{tournamentId}', 'App\Api\Controllers\PushMessagesController@getWebsiteMessages');

    $api->post('tournament/getFilterDropDownData', 'App\Api\Controllers\TournamentController@getFilterDropDownData');

    $api->post('duplicateExistingTournament', 'App\Api\Controllers\TournamentController@duplicateExistingTournament');

    $api->get('compareTemplateJson/{oldId}/{newId}', 'App\Api\Controllers\TemplateController@compareJsonTemplate');

    $api->post('updateTemplateFormDetail', 'App\Api\Controllers\TemplateController@updateTemplateFormDetail');

    $api->post('templateJsonUpdateScript', 'App\Api\Controllers\TemplateController@templateJsonUpdateScript');

    $api->post('scriptForDivisionsAndMinimumMatches', 'App\Api\Controllers\TemplateController@scriptForDivisionsAndMinimumMatches');
});
