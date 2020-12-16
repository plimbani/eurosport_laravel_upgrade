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
    Route::post('check','AuthController@check');

    // Social logins
    Route::post('social/login', 'AuthController@socialLogin');

    //check token validate
    Route::get('token_validate','AuthController@token_validate');
});

Route::get('password/reset/{token}', '\Laraspace\Api\Controllers\PasswordController@getReset');
Route::post('password/reset', '\Laraspace\Api\Controllers\PasswordController@postReset');
//Route::post('password/email', 'Laraspace\Api\Controllers\PasswordController@postEmail');
Route::get('/mlogin', '\Laraspace\Http\Controllers\Auth\ResetPasswordController@userMlogin');


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'signedurl'], function ($api) {
    $api->get('match/report/generate/{ageGroupId}/{tournamentId}',
        'Laraspace\Api\Controllers\MatchController@generateCategoryReport')->name('generate.category.report');
    $api->get('tournament/report/print', 'Laraspace\Api\Controllers\TournamentController@generatePrint');
    $api->get('match/print', 'Laraspace\Api\Controllers\MatchController@generateMatchPrint');
    $api->get('match/reportCard/{refereeId}','Laraspace\Api\Controllers\MatchController@generateRefereeReportCard');
    $api->get('tournament/report/reportExport','Laraspace\Api\Controllers\TournamentController@exportReport');
    $api->get('users/getUserTableData', 'Laraspace\Api\Controllers\UserController@getUserTableData');

    $api->get('teams/getTeamsFairPlayData/report/reportExport', 'Laraspace\Api\Controllers\TeamController@exportTeamFairPlayReport');
    $api->get('teams/getTeamsFairPlayData/report/print', 'Laraspace\Api\Controllers\TeamController@printTeamFairPlayReport');
    $api->get('pitchPlanner/print/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@generatePitchPlannerPrint');
    $api->get('referee/downloadSampleUploadSheet', 'Laraspace\Api\Controllers\RefereeController@downloadSampleUploadSheet');
    $api->get('match/downloadSampleUploadSheet', 'Laraspace\Api\Controllers\MatchController@downloadSampleUploadSheet');
    $api->get('pitchPlanner/export/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@generatePitchPlannerExport');

    $api->get('generateTemplateGraphic/{ageCategoryId}','Laraspace\Api\Controllers\TemplateController@generateTemplateGraphic');
    $api->get('match/schedule/print', 'Laraspace\Api\Controllers\AgeGroupController@generateMatchSchedulePrint');
});

$api->version('v1', function ($api) {
    // Need to check later
    $api->get('pitch/reportCard/{pitchId}', 'Laraspace\Api\Controllers\PitchController@generatePitchMatchReport')->name('pitch.reportcard');
    $api->get('teams/getGroupsViewData/report/print', 'Laraspace\Api\Controllers\TeamController@printGroupsViewReport');

    $api->post('tournaments/getTournamentByStatus', 'Laraspace\Api\Controllers\TournamentController@getTournamentByStatus');
    $api->get('tournaments/getTournamentBySlug/{slug}', 'Laraspace\Api\Controllers\TournamentController@getTournamentBySlug');

    $api->post('tournament/getCategoryCompetitions', 'Laraspace\Api\Controllers\TournamentController@getCategoryCompetitions');
    $api->post('match/getFixtures','Laraspace\Api\Controllers\MatchController@getFixtures');
    $api->post('match/getDrawTable','Laraspace\Api\Controllers\MatchController@getDrawTable');
    $api->post('teams/getAllCompetitionTeamsFromFixture','Laraspace\Api\Controllers\TeamController@getAllCompetitionTeamsFromFixture');

    $api->post('match/refreshStanding', 'Laraspace\Api\Controllers\MatchController@refreshStanding');
    $api->post('tournament/getDropDownData','Laraspace\Api\Controllers\TournamentController@tournamentFilter');
    $api->post('teams/teamsTournament',
        'Laraspace\Api\Controllers\TeamController@getAllTournamentTeams');
    $api->post('password/email', '\Laraspace\Http\Controllers\Auth\ForgotPasswordController@resetlink');

    $api->post('password/reset', '\Laraspace\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.request');
    // $api->get('mlogin', '\Laraspace\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.request');
    $api->get('/mlogin', '\Laraspace\Http\Controllers\Auth\ResetPasswordController@userMlogin');

    $api->get('match/automateMatchScheduleAndResult/{tournamentId?}/{ageGroupId?}','Laraspace\Api\Controllers\MatchController@automateMatchScheduleAndResult')->name('automate.match.result');

    $api->post('appversion', 'Laraspace\Api\Controllers\VersionController@apkVersion');

    $api->post('get_project_configurations', 'Laraspace\Api\Controllers\ProjectConfigurationController@getProjectConfigurations');

    $api->post('age_group/getCompetationFormat',
        'Laraspace\Api\Controllers\AgeGroupController@getCompetationFormat');

    $api->post('match/getDraws', 'Laraspace\Api\Controllers\MatchController@getDraws');

    $api->post('match/getStanding/{refreshStanding?}',
        'Laraspace\Api\Controllers\MatchController@getStanding');

    $api->post('user/create', 'Laraspace\Api\Controllers\UserController@createUser')->name('create.users');

    $api->post('/passwordactivate', '\Laraspace\Api\Controllers\UserController@passwordActivate');
    $api->get('tournaments', 'Laraspace\Api\Controllers\TournamentController@index');

    $api->post('age_group/getPlacingsData','Laraspace\Api\Controllers\AgeGroupController@getPlacingsData');

    $api->post('tournament/getCompetitionAndPitchDetail', 'Laraspace\Api\Controllers\TournamentController@getCompetitionAndPitchDetail');

    $api->post('tournament/scheduleAutomaticPitchPlanning', 'Laraspace\Api\Controllers\TournamentController@scheduleAutomaticPitchPlanning');
    $api->get('/changeWebsiteMenus','Laraspace\Api\Controllers\HomeController@changeWebsiteMenus');

    $api->post('/getAllPitchesWithDays/{pitchId}', 'Laraspace\Api\Controllers\TournamentController@getAllPitchesWithDays');

    $api->post('tournament/updateCompetitionDisplayName', 'Laraspace\Api\Controllers\TournamentController@updateCompetitionDisplayName');

    $api->get('getCountries', 'Laraspace\Api\Controllers\UserController@getAllCountries');
    $api->get('getAllLanguages', 'Laraspace\Api\Controllers\UserController@getAllLanguages');
    $api->post('tournament/updateCategoryDivisionName', 'Laraspace\Api\Controllers\TournamentController@updateCategoryDivisionName');
    $api->post('/userResendEmail', '\Laraspace\Api\Controllers\UserController@userResendEmail');
    $api->get('user/setpasswordCheck/{key}','\Laraspace\Api\Controllers\UserController@setPassword');

    $api->post('deleteFinalPlacingTeam','Laraspace\Api\Controllers\AgeGroupController@deleteFinalPlacingTeam');

    $api->post('getTemplateGraphic','Laraspace\Api\Controllers\TemplateController@getTemplateGraphic');

    $api->post('getSignedUrlForMatchSchedulePrint', 'Laraspace\Api\Controllers\AgeGroupController@getSignedUrlForMatchSchedulePrint');
});

$api->version('v1',['middleware' => 'jwt.auth'], function ($api) {
    // for localization
    $locale = \Request::header('locale');

    if($locale != '') {
        App::setLocale($locale);
    }

    $api->post('teams', 'Laraspace\Api\Controllers\TeamController@getTeams');
    $api->post('team/create', 'Laraspace\Api\Controllers\TeamController@createTeam'); 

    $api->post('team/group/assign', 'Laraspace\Api\Controllers\TeamController@assignTeam');
    $api->post('team/getClubsTeams','Laraspace\Api\Controllers\TeamController@getClubTeams');

    $api->post('team/checkTeamExist', 'Laraspace\Api\Controllers\TeamController@checkTeamExist');

    $api->post('editTeamDetails/{id}', 'Laraspace\Api\Controllers\TeamController@editTeamDetails');
    $api->get('getAllTeamColors', 'Laraspace\Api\Controllers\TeamController@getAllTeamColors');
    $api->get('getAllCountries', 'Laraspace\Api\Controllers\TeamController@getAllCountries');
    $api->get('getAllClubs', 'Laraspace\Api\Controllers\TeamController@getAllClubs');

    $api->post('getClubsByTournamentId/{tournamentId}', 'Laraspace\Api\Controllers\TeamController@getClubsByTournamentId');

    $api->post('updateTeamDetails/{id}', 'Laraspace\Api\Controllers\TeamController@updateTeamDetails');
    $api->post('resetAllTeams', 'Laraspace\Api\Controllers\TeamController@resetAllTeams');
    // Method for get All TournamentTeams

    // Manually change team name
    $api->post('teams/changeTeamName',
        'Laraspace\Api\Controllers\TeamController@changeTeamName');

    //MatchResult api
    $api->post('match/schedule', 'Laraspace\Api\Controllers\MatchController@scheduleMatch');
    $api->post('match/unschedule', 'Laraspace\Api\Controllers\MatchController@unscheduleMatch');
    $api->post('match/fixtureUnschedule', 'Laraspace\Api\Controllers\MatchController@matchUnscheduledFixtures');
    $api->post('match/unscheduleAllFixtures', 'Laraspace\Api\Controllers\MatchController@unscheduleAllFixtures');
    $api->post('saveScheduleMatches', 'Laraspace\Api\Controllers\MatchController@saveScheduleMatches');
    $api->post('match/getScheduledMatch', 'Laraspace\Api\Controllers\MatchController@getScheduledMatch');
    

    $api->post('match/detail', 'Laraspace\Api\Controllers\MatchController@getMatchDetail');
    $api->post('match/removeAssignedReferee', 'Laraspace\Api\Controllers\MatchController@removeAssignedReferee');
    $api->post('match/assignReferee', 'Laraspace\Api\Controllers\MatchController@assignReferee');
    $api->post('match/saveResult', 'Laraspace\Api\Controllers\MatchController@saveResult');
    $api->post('match/saveAllResults', 'Laraspace\Api\Controllers\MatchController@saveAllResults');

    $api->post('match/getUnavailableBlock', 'Laraspace\Api\Controllers\MatchController@getUnavailableBlock');
    $api->post('match/updateScore', 'Laraspace\Api\Controllers\MatchController@updateScore');

    $api->post('match/checkTeamIntervalforMatches',
        'Laraspace\Api\Controllers\MatchController@checkTeamIntervalforMatches');

    $api->post('referees', 'Laraspace\Api\Controllers\RefereeController@getReferees');
    $api->post('referee/create', 'Laraspace\Api\Controllers\RefereeController@createReferee');
    $api->post('referee/update', 'Laraspace\Api\Controllers\RefereeController@updateReferee');

    $api->post('referee/refereeDetail', 'Laraspace\Api\Controllers\RefereeController@refereeDetail');
    $api->post('referee/delete/{deleteid}', 'Laraspace\Api\Controllers\RefereeController@deleteReferee');

    $api->post('referee/uploadExcel', 'Laraspace\Api\Controllers\RefereeController@uploadRefereesExcel'); 

    $api->get('pitches/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getPitches');
    $api->get('getPitchSizeWiseSummary/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getPitchSizeWiseSummary');
    $api->get('getLocationWiseSummary/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getLocationWiseSummary');
    $api->get('pitch/show/{pitchId}', 'Laraspace\Api\Controllers\PitchController@show');
    $api->post('pitch/create', 'Laraspace\Api\Controllers\PitchController@createPitch');
    $api->post('pitch/edit/{id}', 'Laraspace\Api\Controllers\PitchController@edit');
    $api->post('pitch/delete/{deleteid}', 'Laraspace\Api\Controllers\PitchController@deletePitch');
    $api->post('pitch/updatePitchOrder', 'Laraspace\Api\Controllers\PitchController@updatePitchOrder');

    $api->post('age_group/createCompetationFomat','Laraspace\Api\Controllers\AgeGroupController@createCompetationFomat');
    $api->post('age_group/deleteCompetationFormat','Laraspace\Api\Controllers\AgeGroupController@deleteCompetationFormat');

    $api->get('venues/getAll/{tournamentId}', 'Laraspace\Api\Controllers\VenueController@getVenues');

    $api->post('tournament/updateStatus', 'Laraspace\Api\Controllers\TournamentController@updateStatus');

    $api->post('tournaments/templates', 'Laraspace\Api\Controllers\TournamentController@templates');

    $api->post('tournaments/getTemplate', 'Laraspace\Api\Controllers\TournamentController@getTemplate');

    $api->post('tournament/create', 'Laraspace\Api\Controllers\TournamentController@create');
    $api->post('tournament/delete/{id}', 'Laraspace\Api\Controllers\TournamentController@delete');
    $api->post('tournaments/tournamentSummary','Laraspace\Api\Controllers\TournamentController@tournamentSummary');

    $api->post('tournament/details/add',
        'Laraspace\Api\Controllers\TournamentController@addTournamentDetails');

    $api->get('users', 'Laraspace\Api\Controllers\UserController@getUsers');
    $api->get('users1',function() {
    });

    $api->post('users/getUsersByRegisterType',
        'Laraspace\Api\Controllers\UserController@getUsersByRegisterType');

    $api->get('user/edit/{id}', 'Laraspace\Api\Controllers\UserController@edit')->name('edit.users');
    $api->post('user/getDetails', 'Laraspace\Api\Controllers\UserController@getUserDetails');

    $api->post('user/update/{id}', 'Laraspace\Api\Controllers\UserController@update')->name('update.users');
    $api->post('user/delete/{id}', 'Laraspace\Api\Controllers\UserController@deleteUser')->name('delete.users');

    $api->post('user/status', 'Laraspace\Api\Controllers\UserController@changeUserStatus');

    $api->post('users/updatefcm','Laraspace\Api\Controllers\UserController@updatefcm');

    $api->post('users/sendNotification',
        'Laraspace\Api\Controllers\PushMessagesController@sendNotification');
    $api->post('users/getMessage','Laraspace\Api\Controllers\PushMessagesController@getMessages');

    $api->post('/tournament/saveSettings', '\Laraspace\Api\Controllers\TournamentController@saveSettings');
    $api->post('/tournament/saveContactDetails', '\Laraspace\Api\Controllers\TournamentController@saveContactDetails');
    $api->post('/tournament/saveVenueDetails', '\Laraspace\Api\Controllers\TournamentController@saveVenueDetails');
    $api->get('/tournament/getPresentationSettings/{tournamentId}', '\Laraspace\Api\Controllers\TournamentController@getPresentationSettings');

    $api->post('/user/resendEmail', '\Laraspace\Api\Controllers\UserController@resendEmail');

    $api->get('roles', 'Laraspace\Api\Controllers\RoleController@getRoles');

    $api->get('tournament/report/generate',
        'Laraspace\Api\Controllers\TournamentController@generateReport');

    $api->post('users/setFavourite','Laraspace\Api\Controllers\UserController@setFavourite');
    $api->post('users/removeFavourite','Laraspace\Api\Controllers\UserController@removeFavourite');
    $api->post('users/setDefaultFavourite','Laraspace\Api\Controllers\UserController@setDefaultFavourite');

    $api->post('users/getLoginUserFavouriteTournament',
        'Laraspace\Api\Controllers\TournamentController@getUserLoginFavouriteTournament');
    $api->post('tournaments/getTournamentClub',
        'Laraspace\Api\Controllers\TournamentController@getTournamentClub');

    $api->post('teams/getTeamsList','Laraspace\Api\Controllers\TeamController@getTeamsList');
    $api->post('teams/getAllTournamentTeams', 'Laraspace\Api\Controllers\TeamController@getAllTournamentTeams');

    $api->post('users/postSetting','Laraspace\Api\Controllers\UserController@postSetting');
    $api->post('users/getSetting','Laraspace\Api\Controllers\UserController@getSetting');

    $api->post('match/saveStandingsManually', 'Laraspace\Api\Controllers\MatchController@saveStandingsManually');

    $api->post('user/changeTournamentPermission',
        'Laraspace\Api\Controllers\UserController@changeTournamentPermission');
    $api->get('user/getUserTournaments/{id}','Laraspace\Api\Controllers\UserController@getUserTournaments');

    $api->post('tournament/saveCategoryCompetitionColor',
        'Laraspace\Api\Controllers\TournamentController@saveCategoryCompetitionColor');

    // routes for signed url
    $api->post('getSignedUrlForMatchReport', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForMatchReport');

    $api->post('getSignedUrlForTournamentReport', 'Laraspace\Api\Controllers\TournamentController@getSignedUrlForTournamentReport');

    $api->post('getSignedUrlForMatchPrint', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForMatchPrint');

    $api->post('getSignedUrlForRefereeReport/{refereeId}', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForRefereeReport');

    $api->post('getSignedUrlForPitchMatchReport/{pitchId}', 'Laraspace\Api\Controllers\PitchController@getSignedUrlForPitchMatchReport');

    $api->post('getSignedUrlForTournamentReportExport', 'Laraspace\Api\Controllers\TournamentController@getSignedUrlForTournamentReportExport');

    $api->post('getSignedUrlForUsersTableData', 'Laraspace\Api\Controllers\UserController@getSignedUrlForUsersTableData');

    $api->get('getTeamsFairPlayData', 'Laraspace\Api\Controllers\TeamController@getTeamsFairPlayData');
    $api->post('getSignedUrlForTeamsFairPlayReportExport', 'Laraspace\Api\Controllers\TeamController@getSignedUrlForTeamsFairPlayReportExport');

    $api->post('allocateTeamsAutomatically', 'Laraspace\Api\Controllers\TeamController@allocateTeamsAutomatically');

    $api->post('getSignedUrlForFairPlayReportPrint', 'Laraspace\Api\Controllers\TeamController@getSignedUrlForFairPlayReportPrint');

    $api->post('match/saveUnavailableBlock', 'Laraspace\Api\Controllers\MatchController@saveUnavailableBlock');
    $api->post('match/remove_block/{blockId}', 'Laraspace\Api\Controllers\MatchController@removeBlock');
    $api->post('getSignedUrlForPitchPlannerPrint/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getSignedUrlForPitchPlannerPrint');
    $api->post('getSignedUrlForPitchPlannerExport/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getSignedUrlForPitchPlannerExport');

    $api->post('getSignedUrlForRefereeSampleDownload', 'Laraspace\Api\Controllers\RefereeController@getSignedUrlForRefereeSampleDownload');
    $api->post('getSignedUrlForTeamsSpreadsheetSampleDownload', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForTeamsSpreadsheetSampleDownload');

    $api->post('getTemplates', 'Laraspace\Api\Controllers\TemplateController@getTemplates');
    $api->post('getTemplateDetail', 'Laraspace\Api\Controllers\TemplateController@getTemplateDetail');
    $api->get('templates/getUsersForFilter', 'Laraspace\Api\Controllers\TemplateController@getUsersForFilter');
    $api->post('template/delete/{id}', 'Laraspace\Api\Controllers\TemplateController@deleteTemplate');
    $api->get('template/edit/{id}', 'Laraspace\Api\Controllers\TemplateController@editTemplate');
    $api->post('saveTemplateDetail', 'Laraspace\Api\Controllers\TemplateController@saveTemplateDetail');
    $api->post('updateTemplateDetail', 'Laraspace\Api\Controllers\TemplateController@updateTemplateDetail');
    
    $api->post('age_group/copyAgeCategory','Laraspace\Api\Controllers\AgeGroupController@copyAgeCategory');
    $api->post('viewGraphicImage','Laraspace\Api\Controllers\AgeGroupController@viewTemplateGraphicImage');

    $api->post('duplicateTournament','Laraspace\Api\Controllers\TournamentController@duplicateTournament');
    
    $api->post('duplicateTournamentList','Laraspace\Api\Controllers\TournamentController@duplicateTournamentList');

    $api->post('updateAppDeviceVersion','Laraspace\Api\Controllers\UserController@updateAppDeviceVersion');

    $api->post('getTournamentTeamDetails','Laraspace\Api\Controllers\TeamController@getTournamentTeamDetails');

    $api->post('pitchSearchRecord', 'Laraspace\Api\Controllers\PitchController@getPitchSearchRecord');

    $api->post('getVenuesDropDownData', 'Laraspace\Api\Controllers\PitchController@getVenuesDropDownData');
    
    $api->post('getSignedUrlForGroupsViewReport', 'Laraspace\Api\Controllers\TeamController@getSignedUrlForGroupsViewReport');

    $api->post('user/validateemail','Laraspace\Api\Controllers\UserController@validateUserEmail');
    
    $api->post('user/verifyResultAdminUser','Laraspace\Api\Controllers\UserController@verifyResultAdminUser');

    $api->get('getMatchesAndStandingsOfAgeCategory/{ageCategoryId}','Laraspace\Http\Controllers\PresentationController@getMatchesAndStandingsOfAgeCategory');
});

// Websites CMS routes
$api->version('v1',['middleware' => 'jwt.auth'], function ($api) {
    // Published tournaments
    $api->get('getAllPublishedTournaments','Laraspace\Api\Controllers\TournamentController@getAllPublishedTournaments');

    // Website & Tournament permissions
    $api->post('user/changePermissions','Laraspace\Api\Controllers\UserController@changePermissions');
    $api->get('user/getUserWebsites/{id}','Laraspace\Api\Controllers\UserController@getUserWebsites');

    // Websites APIs
    $api->get('websites', 'Laraspace\Api\Controllers\WebsiteController@index');
    $api->get('getUserAccessibleWebsites', 'Laraspace\Api\Controllers\WebsiteController@getUserAccessibleWebsites');
    $api->post('saveWebsiteData', 'Laraspace\Api\Controllers\WebsiteController@saveWebsiteData');
    $api->post('websites/websiteSummary', 'Laraspace\Api\Controllers\WebsiteController@websiteSummary');
    $api->get('websites/customisation/options', 'Laraspace\Api\Controllers\WebsiteController@getWebsiteCustomisationOptions');
    $api->get('websites/getWebsiteDefaultPages', 'Laraspace\Api\Controllers\WebsiteController@getWebsiteDefaultPages');

    $api->post('websites/uploadTournamentLogo', 'Laraspace\Api\Controllers\WebsiteController@uploadTournamentLogo');
    $api->post('websites/uploadSocialGraphic', 'Laraspace\Api\Controllers\WebsiteController@uploadSocialGraphic');
    $api->post('websites/uploadSponsorImage', 'Laraspace\Api\Controllers\WebsiteController@uploadSponsorImage');
    $api->post('websites/uploadHeroImage', 'Laraspace\Api\Controllers\WebsiteController@uploadHeroImage');
    $api->post('websites/uploadWelcomeImage', 'Laraspace\Api\Controllers\WebsiteController@uploadWelcomeImage');
    $api->post('websites/uploadOrganiserLogo', 'Laraspace\Api\Controllers\WebsiteController@uploadOrganiserLogo');

    $api->get('getWebsiteDetails/{websiteId}', 'Laraspace\Api\Controllers\WebsiteController@getWebsiteDetails');

    //Website homepage
    $api->get('getStatistics/{websiteId}', 'Laraspace\Api\Controllers\HomeController@getStatistics');
    $api->get('getOrganisers/{websiteId}', 'Laraspace\Api\Controllers\HomeController@getOrganisers');
    $api->get('getSponsors/{websiteId}', 'Laraspace\Api\Controllers\WebsiteController@getSponsors');
    $api->post('saveHomePageData', 'Laraspace\Api\Controllers\HomeController@savePageData');

    $api->post('saveWebsiteTournamentPageData', 'Laraspace\Api\Controllers\WebsiteTournamentController@savePageData');
    $api->get('getWebsiteTournamentPageData/{websiteId}', 'Laraspace\Api\Controllers\WebsiteTournamentController@getPageData');

    $api->get('getHomePageData/{websiteId}', 'Laraspace\Api\Controllers\HomeController@getPageData');

    //Website staypage
    $api->post('saveStayPageData', 'Laraspace\Api\Controllers\StayController@saveStayPageData');
    $api->get('getStayPageData/{websiteId}', 'Laraspace\Api\Controllers\StayController@getStayPageData');

    // Website programpage
    $api->get('getItineraries/{websiteId}', 'Laraspace\Api\Controllers\ProgramController@getItineraries');
    $api->post('saveProgramPageData', 'Laraspace\Api\Controllers\ProgramController@saveProgramPageData');
    $api->get('getProgramPageData/{websiteId}', 'Laraspace\Api\Controllers\ProgramController@getProgramPageData');

    // Image path
    $api->get('getConfigurationDetail', 'Laraspace\Api\Controllers\WebsiteController@getConfigurationDetail');

    // Website visitor
    $api->post('saveVisitorPageData', 'Laraspace\Api\Controllers\VisitorController@savePageData');
    $api->get('getVisitorPageData/{websiteId}', 'Laraspace\Api\Controllers\VisitorController@getPageData');

    // Website team
    $api->get('getAgeCategories/{websiteId}', 'Laraspace\Api\Controllers\WebsiteTeamController@getAgeCategories');
    $api->get('getTeamPageData/{websiteId}', 'Laraspace\Api\Controllers\WebsiteTeamController@getPageData');
    $api->post('saveTeamPageData', 'Laraspace\Api\Controllers\WebsiteTeamController@savePageData');
    $api->post('importAgeCategoryAndTeamData', 'Laraspace\Api\Controllers\WebsiteTeamController@importAgeCategoryAndTeamData');

    // Website media
    $api->get('getPhotos/{websiteId}', 'Laraspace\Api\Controllers\MediaController@getPhotos');
    $api->get('getDocuments/{websiteId}', 'Laraspace\Api\Controllers\MediaController@getDocuments');
    $api->post('saveMediaPageData', 'Laraspace\Api\Controllers\MediaController@savePageData');
    $api->post('media/uploadMediaPhoto', 'Laraspace\Api\Controllers\MediaController@uploadMediaPhoto');
    $api->post('media/uploadDocument', 'Laraspace\Api\Controllers\MediaController@uploadDocument');
    // Website venue
    $api->get('getLocations/{websiteId}', 'Laraspace\Api\Controllers\WebsiteVenueController@getLocations');
    $api->get('getMarkers/{websiteId}', 'Laraspace\Api\Controllers\WebsiteVenueController@getMarkers');
    $api->post('saveVenuePageData', 'Laraspace\Api\Controllers\WebsiteVenueController@savePageData');

    // Contact
    $api->get('getContactDetails/{websiteId}', 'Laraspace\Api\Controllers\ContactController@getContactDetails');
    $api->post('saveContactDetails', 'Laraspace\Api\Controllers\ContactController@saveContactDetails');

    $api->post('uploadImage', 'Laraspace\Api\Controllers\UploadMediaController@uploadImage');

    $api->post('generatePreviewUrl/{websiteId}', 'Laraspace\Api\Controllers\WebsiteController@generatePreviewUrl');
    
});

// Website frontend API calls
$api->version('v1', function ($api) {
    $api->get('getWebsiteMessages/{tournamentId}', 'Laraspace\Api\Controllers\PushMessagesController@getWebsiteMessages');

    $api->post('tournament/getFilterDropDownData','Laraspace\Api\Controllers\TournamentController@getFilterDropDownData');

    $api->post('duplicateExistingTournament', 'Laraspace\Api\Controllers\TournamentController@duplicateExistingTournament');

    $api->get('compareTemplateJson/{oldId}/{newId}', 'Laraspace\Api\Controllers\TemplateController@compareJsonTemplate');

    $api->post('updateTemplateFormDetail', 'Laraspace\Api\Controllers\TemplateController@updateTemplateFormDetail');

    $api->post('templateJsonUpdateScript', 'Laraspace\Api\Controllers\TemplateController@templateJsonUpdateScript');

    $api->post('scriptForDivisionsAndMinimumMatches', 'Laraspace\Api\Controllers\TemplateController@scriptForDivisionsAndMinimumMatches');
});
