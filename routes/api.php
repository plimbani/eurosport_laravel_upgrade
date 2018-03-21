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
});

Route::get('password/reset/{token}', '\Laraspace\Api\Controllers\PasswordController@getReset');
Route::post('password/reset', '\Laraspace\Api\Controllers\PasswordController@postReset');
//Route::post('password/email', 'Laraspace\Api\Controllers\PasswordController@postEmail');
Route::get('/mlogin', '\Laraspace\Http\Controllers\Auth\ResetPasswordController@userMlogin');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'signedurl'], function ($api) {
    $api->get('match/report/generate/{ageGroupId}',
        'Laraspace\Api\Controllers\MatchController@generateCategoryReport')->name('generate.category.report');
    $api->get('tournament/report/print', 'Laraspace\Api\Controllers\TournamentController@generatePrint');
    $api->get('match/print', 'Laraspace\Api\Controllers\MatchController@generateMatchPrint');
    $api->get('match/reportCard/{refereeId}','Laraspace\Api\Controllers\MatchController@generateRefereeReportCard');
    $api->get('pitch/reportCard/{pitchId}', 'Laraspace\Api\Controllers\PitchController@generatePitchMatchReport');
    $api->get('tournament/report/reportExport','Laraspace\Api\Controllers\TournamentController@exportReport');
});

$api->version('v1', function ($api) {
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
    $api->get('users/getUserTableData', 'Laraspace\Api\Controllers\UserController@getUserTableData');

    $api->get('match/automateMatchScheduleAndResult/{tournamentId?}/{ageGroupId?}','Laraspace\Api\Controllers\MatchController@automateMatchScheduleAndResult')->name('automate.match.result');

    $api->get('tournaments', 'Laraspace\Api\Controllers\TournamentController@index');

    $api->post('appversion', 'Laraspace\Api\Controllers\VersionController@apkVersion');

    $api->post('match/saveStandingsManually',
        'Laraspace\Api\Controllers\MatchController@saveStandingsManually');

    $api->post('age_group/getCompetationFormat',
        'Laraspace\Api\Controllers\AgeGroupController@getCompetationFormat');

    $api->post('match/getDraws', 'Laraspace\Api\Controllers\MatchController@getDraws');

    $api->post('match/getStanding/{refreshStanding?}',
        'Laraspace\Api\Controllers\MatchController@getStanding');

    $api->post('user/create', 'Laraspace\Api\Controllers\UserController@createUser')->name('create.users');

    // routes for sigend url
    $api->post('getSignedUrlForMatchReport/{ageCategory}', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForMatchReport');

    $api->post('getSignedUrlForTournamentReport', 'Laraspace\Api\Controllers\TournamentController@getSignedUrlForTournamentReport');

    $api->post('getSignedUrlForMatchPrint', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForMatchPrint');

    $api->post('getSignedUrlForRefereeReport/{refereeId}', 'Laraspace\Api\Controllers\MatchController@getSignedUrlForRefereeReport');

    $api->post('getSignedUrlForPitchMatchReport/{pitchId}', 'Laraspace\Api\Controllers\PitchController@getSignedUrlForPitchMatchReport');

    $api->post('getSignedUrlForTournamentReportExport', 'Laraspace\Api\Controllers\TournamentController@getSignedUrlForTournamentReportExport');
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
    $api->get('getAllCountries', 'Laraspace\Api\Controllers\TeamController@getAllCountries');
    $api->get('getAllClubs', 'Laraspace\Api\Controllers\TeamController@getAllClubs');

    $api->post('updateTeamDetails/{id}', 'Laraspace\Api\Controllers\TeamController@updateTeamDetails');
    $api->post('resetAllTeams', 'Laraspace\Api\Controllers\TeamController@resetAllTeams');
    // Method for get All TournamentTeams

    // Manually change team name
    $api->post('teams/changeTeamName',
        'Laraspace\Api\Controllers\TeamController@changeTeamName');

    //MatchResult api
    $api->post('match/schedule', 'Laraspace\Api\Controllers\MatchController@scheduleMatch');
    $api->post('match/unschedule', 'Laraspace\Api\Controllers\MatchController@unscheduleMatch');
    $api->post('match/detail', 'Laraspace\Api\Controllers\MatchController@getMatchDetail');
    // $api->get('match/report', 'Laraspace\Api\Controllers\MatchController@generateMatchPrint');
    $api->post('match/removeAssignedReferee', 'Laraspace\Api\Controllers\MatchController@removeAssignedReferee');
    $api->post('match/assignReferee', 'Laraspace\Api\Controllers\MatchController@assignReferee');
    $api->post('match/saveResult', 'Laraspace\Api\Controllers\MatchController@saveResult');
    $api->post('match/saveAllResults', 'Laraspace\Api\Controllers\MatchController@saveAllResults');
    $api->post('match/getUnavailableBlock', 'Laraspace\Api\Controllers\MatchController@getUnavailableBlock');
    $api->post('match/updateScore', 'Laraspace\Api\Controllers\MatchController@updateScore');

    $api->post('match/checkTeamIntervalforMatches',
        'Laraspace\Api\Controllers\MatchController@checkTeamIntervalforMatches');



    //Referee api
    $api->post('referees', 'Laraspace\Api\Controllers\RefereeController@getReferees');
    $api->post('referee/create', 'Laraspace\Api\Controllers\RefereeController@createReferee');
    $api->post('referee/update', 'Laraspace\Api\Controllers\RefereeController@updateReferee');

    $api->post('referee/refereeDetail', 'Laraspace\Api\Controllers\RefereeController@refereeDetail');
    $api->post('referee/delete/{deleteid}', 'Laraspace\Api\Controllers\RefereeController@deleteReferee');



    //pitch api
    $api->get('pitches/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getPitches');
    $api->get('getPitchSizeWiseSummary/{tournamentId}', 'Laraspace\Api\Controllers\PitchController@getPitchSizeWiseSummary');
    $api->get('pitch/show/{pitchId}', 'Laraspace\Api\Controllers\PitchController@show');
    $api->post('pitch/create', 'Laraspace\Api\Controllers\PitchController@createPitch');
    $api->post('pitch/edit/{id}', 'Laraspace\Api\Controllers\PitchController@edit');
    $api->post('pitch/delete/{deleteid}', 'Laraspace\Api\Controllers\PitchController@deletePitch');

    //Age Group Stuff
    $api->post('age_group/createCompetationFomat','Laraspace\Api\Controllers\AgeGroupController@createCompetationFomat');
    $api->post('age_group/deleteCompetationFormat','Laraspace\Api\Controllers\AgeGroupController@deleteCompetationFormat');

    // placings data route
    $api->post('age_group/getPlacingsData','Laraspace\Api\Controllers\AgeGroupController@getPlacingsData');

    $api->get('venues/getAll/{tournamentId}', 'Laraspace\Api\Controllers\VenueController@getVenues');


    //Tournament Api CRUD Routes


    // Get Tournament Details By Status

    $api->post('tournament/updateStatus', 'Laraspace\Api\Controllers\TournamentController@updateStatus');


    // Get All Templates
    $api->post('tournaments/templates', 'Laraspace\Api\Controllers\TournamentController@templates');

    $api->post('tournaments/getTemplate', 'Laraspace\Api\Controllers\TournamentController@getTemplate');

    $api->post('tournament/create', 'Laraspace\Api\Controllers\TournamentController@create');
    $api->post('tournament/delete/{id}', 'Laraspace\Api\Controllers\TournamentController@delete');
    $api->post('tournaments/tournamentSummary','Laraspace\Api\Controllers\TournamentController@tournamentSummary');

    $api->post('tournament/details/add',
        'Laraspace\Api\Controllers\TournamentController@addTournamentDetails');

    // User Stuff
    $api->get('users', 'Laraspace\Api\Controllers\UserController@getUsers');
    $api->get('users1',function() {
       // echo 'Hello'.$_SERVER['REMOTE_ADDR'];

    });

    $api->post('users/getUsersByRegisterType',
        'Laraspace\Api\Controllers\UserController@getUsersByRegisterType');

    $api->get('user/edit/{id}', 'Laraspace\Api\Controllers\UserController@edit')->name('edit.users');
    $api->post('user/getDetails', 'Laraspace\Api\Controllers\UserController@getUserDetails');

    $api->post('user/update/{id}', 'Laraspace\Api\Controllers\UserController@update')->name('update.users');
    $api->post('user/delete/{id}', 'Laraspace\Api\Controllers\UserController@deleteUser')->name('delete.users');

    $api->post('user/status', 'Laraspace\Api\Controllers\UserController@changeUserStatus');
    // Push Notification Service
    // Update user for update user id
    $api->post('users/updatefcm','Laraspace\Api\Controllers\UserController@updatefcm');
    $api->post('users/getAllAppUsers', 'Laraspace\Api\Controllers\UserController@getAllAppUsers');
     $api->post('users/sendNotification',
        'Laraspace\Api\Controllers\PushMessagesController@sendNotification');
     $api->post('users/getMessage','Laraspace\Api\Controllers\PushMessagesController@getMessages');

    // End of Push Notification code
    $api->get('/passwordactivate', '\Laraspace\Api\Controllers\UserController@passwordActivate');


    //resend email
    $api->post('/user/resendEmail', '\Laraspace\Api\Controllers\UserController@resendEmail');


    // Role Stuff
    $api->get('roles', 'Laraspace\Api\Controllers\RoleController@getRoles');

    $api->get('tournament/report/generate',
        'Laraspace\Api\Controllers\TournamentController@generateReport');


    // Some specefi Api for Mobile Users
    $api->post('users/setFavourite','Laraspace\Api\Controllers\UserController@setFavourite');
    $api->post('users/removeFavourite','Laraspace\Api\Controllers\UserController@removeFavourite');
    $api->post('users/setDefaultFavourite','Laraspace\Api\Controllers\UserController@setDefaultFavourite');
    $api->post('users/getLoginUserDefaultTournament',
        'Laraspace\Api\Controllers\TournamentController@getUserLoginDefaultTournament');
     $api->post('users/getLoginUserFavouriteTournament',
        'Laraspace\Api\Controllers\TournamentController@getUserLoginFavouriteTournament');
    $api->post('tournaments/getTournamentClub',
        'Laraspace\Api\Controllers\TournamentController@getTournamentClub');

    $api->post('teams/getTeamsList','Laraspace\Api\Controllers\TeamController@getTeamsList');

    $api->post('users/postSetting','Laraspace\Api\Controllers\UserController@postSetting');
    $api->post('users/getSetting','Laraspace\Api\Controllers\UserController@getSetting');
    $api->post('users/updateProfileImage','Laraspace\Api\Controllers\UserController@setUserImage');



    $api->post('user/changeTournamentPermission',
        'Laraspace\Api\Controllers\UserController@changeTournamentPermission');
    $api->get('user/getUserTournaments/{id}','Laraspace\Api\Controllers\UserController@getUserTournaments');


    $api->post('tournament/saveCategoryCompetitionColor',
        'Laraspace\Api\Controllers\TournamentController@saveCategoryCompetitionColor');
});


