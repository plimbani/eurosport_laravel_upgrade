<?php

namespace Laraspace\Api\Controllers;

use UrlSigner;
use Carbon\Carbon;
use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Routing\Controller;
use Laraspace\Models\Team;
use Illuminate\Http\Request;
use Laraspace\Models\TempFixture;
use Laraspace\Http\Requests\Team\StoreRequest;
use Laraspace\Http\Requests\Team\UpdateRequest;
use Laraspace\Http\Requests\Team\AllClubsRequest;
use Laraspace\Http\Requests\Team\GetTeamsRequest;
use Laraspace\Http\Requests\Team\TeamsListRequest;
use Laraspace\Http\Requests\Team\ClubsTeamsRequest;
use Laraspace\Http\Requests\Team\AssignTeamRequest;
use Laraspace\Http\Requests\Team\TeamDetailsRequest;
use Laraspace\Models\TournamentCompetationTemplates;
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
        $file = $request->file('fileUpload');
        //$this->data['tournamentId'] = $teamData['tournamentId'];
        $allAgeCategories = TournamentCompetationTemplates::where('tournament_id', $teamData['tournamentId'])->select('id', 'category_age', 'total_teams')->get()->keyBy('id')->toArray();
        $resultEnteredAgeCategories = TempFixture::where('temp_fixtures.tournament_id', $teamData['tournamentId'])
        ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'temp_fixtures.age_group_id')
        ->where(function ($query) {
            $query->whereNotNull('hometeam_score')
              ->orWhereNotNull('awayteam_score');
        })->get()->pluck('category_age')->unique()->values()->all();
        $alreadyUploadedTeams = Team::where('tournament_id', $teamData['tournamentId'])->get();
        $alreadyUploadedTeamsByAgeCategory = $alreadyUploadedTeams->groupBy('age_category_name')->toArray();
        $alreadyUploadedTeams = $alreadyUploadedTeams->toArray();
        $nonExistingAgeCategories = [];
        $teamNotMatchingAgeCategories = [];
        $teamsNotUploadedOfAgeCategory = [];
        $teamsInDifferentAgeCategory = [];
        $notProcessedAgeCategoriesDueToResultEntered = [];
        $allTeams = [];
        \Excel::selectSheetsByIndex(0)->load($file->getRealPath(), function($reader) use(&$allTeams, $alreadyUploadedTeams, &$teamsNotUploadedOfAgeCategory, &$teamsInDifferentAgeCategory, $alreadyUploadedTeamsByAgeCategory, $resultEnteredAgeCategories, &$notProcessedAgeCategoriesDueToResultEntered) {
            //$this->data['totalSize']  = $reader->getTotalRowsOfFile() - 1;
            $furtherNotToProcessAgeCategories = [];
            $reader->each(function($sheet) use(&$allTeams, $alreadyUploadedTeams, &$teamsNotUploadedOfAgeCategory, &$teamsInDifferentAgeCategory, $alreadyUploadedTeamsByAgeCategory, &$notProcessedAgeCategoriesDueToResultEntered, $resultEnteredAgeCategories, &$furtherNotToProcessAgeCategories) {
              //$sheet->tournamentData = $this->data;
              $ageCategory = trim($sheet['agecategory']);
              if($ageCategory != '') {
                $toProcessTeam = true;
                if(in_array($ageCategory, $furtherNotToProcessAgeCategories)) {
                  $toProcessTeam = false;
                }
                if($toProcessTeam) {
                  $teamExist = array_filter($alreadyUploadedTeams, function($team) use($sheet) {
                    return ($team['esr_reference'] == $sheet['teamid']);
                  });
                  $resultMatchingAgeCategory = array_filter($resultEnteredAgeCategories, function($category) use($ageCategory){
                    return $ageCategory == $category;
                  });
                  if(count($resultMatchingAgeCategory) > 0) {
                    $notProcessedAgeCategoriesDueToResultEntered[] = $ageCategory;
                    $furtherNotToProcessAgeCategories[] = $ageCategory;
                    $toProcessTeam = false;
                  }
                  if((count($teamExist) > 0 && $ageCategory != current($teamExist)['age_category_name'])) {
                    $teamsInDifferentAgeCategory[$ageCategory][] = ['team_id' => $sheet['teamid'], 'team_name' => current($teamExist)['name']];
                    if(!isset($alreadyUploadedTeamsByAgeCategory[$ageCategory])) {
                      $furtherNotToProcessAgeCategories[] = $ageCategory;
                    }
                    $toProcessTeam = false;
                  }
                  if(isset($alreadyUploadedTeamsByAgeCategory[$ageCategory]) && count($teamExist) == 0) {
                    $teamsNotUploadedOfAgeCategory[$ageCategory][] = ['team_id' => $sheet['teamid'], 'team_name' => $sheet['team']];
                    $toProcessTeam = false;
                  }
                  if($toProcessTeam) {
                    $allTeams[$ageCategory][] = $sheet;
                  }
                }
              }
            });
        }, 'ISO-8859-1');


        foreach($allTeams as $ageCategory=>$ageCategoryTeams) {
          $matchingAgeCategoryArray = array_filter($allAgeCategories, function($category) use($ageCategory){
            return $ageCategory == $category['category_age'];
          });
          if(count($matchingAgeCategoryArray) === 0) {
            $nonExistingAgeCategories[] = $ageCategory;
            continue;
          }
          $totalTeams = array_sum(array_column($matchingAgeCategoryArray, 'total_teams'));
          if(!isset($alreadyUploadedTeamsByAgeCategory[$ageCategory]) && $totalTeams !== count($ageCategoryTeams)) {
            $teamNotMatchingAgeCategories[] = $ageCategory;
            continue;
          }
          foreach($ageCategoryTeams as $team) {
            $this->teamObj->create($team, $teamData['tournamentId']);
          }
        }

        return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted', 'teamNotMatchingAgeCategories' => $teamNotMatchingAgeCategories, 'nonExistingAgeCategories' => $nonExistingAgeCategories, 'teamsNotUploadedOfAgeCategory' => $teamsNotUploadedOfAgeCategory, 'teamsInDifferentAgeCategory' => $teamsInDifferentAgeCategory, 'notProcessedAgeCategoriesDueToResultEntered' => $notProcessedAgeCategoriesDueToResultEntered];
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
