<?php

namespace Laraspace\Api\Controllers;

use UrlSigner;
use Carbon\Carbon;
use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Laraspace\Http\Requests\Team\StoreRequest;
use Laraspace\Http\Requests\Team\UpdateRequest;
use Laraspace\Http\Requests\Team\AllClubsRequest;
use Laraspace\Http\Requests\Team\GetTeamsRequest;
use Laraspace\Http\Requests\Team\TeamsListRequest;
use Laraspace\Http\Requests\Team\ClubsTeamsRequest;
use Laraspace\Http\Requests\Team\AssignTeamRequest;
use Laraspace\Http\Requests\Team\TeamDetailsRequest;
use Laraspace\Http\Requests\Team\AllCountriesRequest;
use Laraspace\Http\Requests\Team\AllTeamColorsRequest;
use Laraspace\Http\Requests\Team\ResetAllTeamsRequest;
use Laraspace\Http\Requests\Team\ChangeTeamNameRequest;
use Laraspace\Http\Requests\Team\CheckTeamExistRequest;
use Laraspace\Http\Requests\Team\GetAllTournamentTeamsRequest;
use Laraspace\Http\Requests\Team\GetAllCompetitionTeamsFromFixtureRequest;
use Laraspace\Http\Requests\Team\GetSignedUrlForTeamsFairPlayReportPrint;
use Laraspace\Http\Requests\Team\GetSignedUrlForTeamsFairPlayReportExport;
use Laraspace\Http\Requests\Team\GetTournamentTeamDetailsRequest;
use Laraspace\Http\Requests\Team\GetSignedUrlForGroupsViewReportRequest;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\TeamContract;

/**
 * Teams Resource Description.
 *
 * @Resource("Teams")
 *
 * @Author Knayak@aecordigital.com
 */
class TeamController extends BaseController
{
    public function __construct(TeamContract $teamObj)
    {
        $this->teamObj = $teamObj;
        $this->data = [];
    }

    /**
     * Show all Team Details.
     *
     * Get a JSON representation of all the Teams.
     *
     * @Get("/teams")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getTeams(GetTeamsRequest $request)
    {
       return  $result = $this->teamObj->getTeams($request);
    }

    public function getClubs(Request $request)
    {
        return $this->teamObj->getClubs($request->all());
    }

    public function getClubTeams(ClubsTeamsRequest $request)
    {
        return $this->teamObj->getClubTeams($request->all());
    }

    public function getAllTournamentTeams(GetAllTournamentTeamsRequest $request)
    {
      return $this->teamObj->getAllTournamentTeams($request->all());
    }
    public function getAllFromCompetitionId(Request $request)
    {
      return $this->teamObj->getAllFromCompetitionId($request->all());
    }


    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/team/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */

    public function createTeam(StoreRequest $request)
    {
        $teamData = $request->all();
        // dd($teamData);
        $file = $request->file('fileUpload');
        // $this->data['teamSize'] =  $teamData['teamSize'];
        $this->data['tournamentId'] = $teamData['tournamentId'];
       // $rows = \Excel::load($file->getRealPath(), null, 'ISO-8859-1')->get();
        //print_r($rows);
        //exit;
        \Excel::selectSheetsByIndex(0)->load($file->getRealPath(), function($reader) {
            // dd($reader->getTotalRowsOfFile() - 1);
            $this->data['totalSize']  = $reader->getTotalRowsOfFile() - 1;
            // dd($this->data['totalSize']);
            // $reader->limit($this->data['teamSize']);
            $reader->each(function($sheet) {
                // dd($sheet);
            // Loop through all rows
                // $sheet->each(function($row) {
                    // dd($sheet);
              $sheet->tournamentData = $this->data;
              $this->teamObj->create($sheet);

                // });
            });
        }, 'ISO-8859-1');
            return ['bigFileSize' =>  false];
     
    }
    public function assignTeam(AssignTeamRequest $request) {        
        return $this->teamObj->assignTeams($request->all());
    }
    public function getAllTeamsGroup(Request $request) {
        $this->teamObj->getAllTeamsGroup($request->all());
    }

    // public function importTeamlist(){


    // }
    /**
     * Edit  Teams.
     *
     * @Post("/team/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->teamObj->edit($request);
    }

    /**
     * Delete  Teams.
     *
     * @Post("/team/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(Request $request)
    {
        return $this->teamObj->delete($request);
    }

    public function getTeamsList(TeamsListRequest $request)
    {
      return $this->teamObj->getTeamsList($request->all());
    }

    public function changeTeamName(ChangeTeamNameRequest $request)
    {
        return $this->teamObj->changeTeamName($request->all());
    }

    public function getAllCompetitionTeamsFromFixture(GetAllCompetitionTeamsFromFixtureRequest $request)
    {
      return $this->teamObj->getAllCompetitionTeamsFromFixture($request->all());
    }

    public function editTeamDetails(TeamDetailsRequest $request, $teamId) 
    {
        return $this->teamObj->editTeamDetails($teamId);
    }

    public function getAllTeamColors(AllTeamColorsRequest $request)
    {
        return $this->teamObj->getAllTeamColors();
    }

    public function getAllCountries(AllCountriesRequest $request)
    {
        return $this->teamObj->getAllCountries();
    }

    public function getAllClubs(AllClubsRequest $request)
    {
        return $this->teamObj->getAllClubs();
    }

    public function updateTeamDetails(UpdateRequest $request, $teamId)
    {
        return $this->teamObj->updateTeamDetails($request, $teamId);
    }

    public function checkTeamExist(CheckTeamExistRequest $request)
    {
        return $this->teamObj->checkTeamExist($request);
    }

    public function resetAllTeams(ResetAllTeamsRequest $request)
    {
        return $this->teamObj->resetAllTeams($request);
    }

    public function getClubsByTournamentId(Request $request, $tournamentId)
    {
        return $this->teamObj->getClubsByTournamentId($tournamentId);
    }

    public function getTeamsFairPlayData(Request $request)
    {
        return $this->teamObj->getTeamsFairPlayData($request->all());   
    }

    public function getSignedUrlForTeamsFairPlayReportExport(GetSignedUrlForTeamsFairPlayReportExport $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData  = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(url('api/teams/getTeamsFairPlayData/report/reportExport?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getSignedUrlForFairPlayReportPrint(GetSignedUrlForTeamsFairPlayReportPrint $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData  = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(url('api/teams/getTeamsFairPlayData/report/print?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function exportTeamFairPlayReport(Request $request)
    {
        return $this->teamObj->exportTeamFairPlayReport($request->all());
    }

    public function printTeamFairPlayReport(Request $request)
    {
        return $this->teamObj->printTeamFairPlayReport($request->all());   
    }

    public function getTournamentTeamDetails(GetTournamentTeamDetailsRequest $request) 
    {
      return $this->teamObj->getTournamentTeamDetails($request->all());
    }

    public function getSignedUrlForGroupsViewReport(GetSignedUrlForGroupsViewReportRequest $request)
    {
      $groupsViewData = $request->all();
      ksort($groupsViewData);
      $groupsViewData  = http_build_query($groupsViewData);
      $signedUrl = UrlSigner::sign(url('api/teams/getGroupsViewData/report/print?' . $groupsViewData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

      return $signedUrl;
    }

    public function printGroupsViewReport(Request $request)
    {
      return $this->teamObj->printGroupsViewReport($request->all());
    }
}
