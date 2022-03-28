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
        $allAgeCategories = TournamentCompetationTemplates::where('tournament_id', $teamData['tournamentId'])->select('id', 'category_age', 'group_name', 'total_teams')->get()->keyBy('id')->toArray();
        $resultEnteredAgeCategories = TempFixture::where('temp_fixtures.tournament_id', $teamData['tournamentId'])
        ->leftjoin('tournament_competation_template', 'tournament_competation_template.id', '=', 'temp_fixtures.age_group_id')
        ->where(function ($query) {
            $query->whereNotNull('hometeam_score')
              ->orWhereNotNull('awayteam_score');
        })->select(['category_age', 'group_name'])->get()->toArray();
        $alreadyUploadedTeams = Team::where('tournament_id', $teamData['tournamentId'])->get();
        $alreadyUploadedTeamsByAgeCategory = $alreadyUploadedTeams->groupBy('age_group_id')->toArray();
        $alreadyUploadedTeams = $alreadyUploadedTeams->toArray();
        $nonExistingAgeCategories = [];
        $teamNotMatchingAgeCategories = [];
        $teamsNotUploadedOfAgeCategory = [];
        $teamsInDifferentAgeCategory = [];
        $notProcessedAgeCategoriesDueToResultEntered = [];
        $furtherNotToProcessAgeCategories = [];
        $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet = [];
        $allTeamsInSheet = [];
        $allTeams = [];
        $clubValidation = false;
        $teamValidation = false;
        $placeValidation = false;
        $ageCategoryValidation = false;
        \Excel::selectSheetsByIndex(0)->load($file->getRealPath(), function($reader) use(&$allTeams, $alreadyUploadedTeams, &$teamsNotUploadedOfAgeCategory, &$teamsInDifferentAgeCategory, $alreadyUploadedTeamsByAgeCategory, $resultEnteredAgeCategories, &$notProcessedAgeCategoriesDueToResultEntered, &$furtherNotToProcessAgeCategories, $allAgeCategories, &$nonExistingAgeCategories, &$notProcessedAgeCategoriesDuetoSameTeamInUploadSheet, &$allTeamsInSheet, &$clubValidation, &$teamValidation, &$placeValidation, &$ageCategoryValidation) {
            $reader->each(function($sheet) use(&$allTeams, $alreadyUploadedTeams, &$teamsNotUploadedOfAgeCategory, &$teamsInDifferentAgeCategory, $alreadyUploadedTeamsByAgeCategory, &$notProcessedAgeCategoriesDueToResultEntered, $resultEnteredAgeCategories, &$furtherNotToProcessAgeCategories, $allAgeCategories, &$nonExistingAgeCategories, &$notProcessedAgeCategoriesDuetoSameTeamInUploadSheet, &$allTeamsInSheet, &$clubValidation, &$teamValidation, &$placeValidation, &$ageCategoryValidation) {
              if (!$sheet->filter()->isEmpty()) {
                //$sheet->tournamentData = $this->data;
                $club = trim($sheet['club']);
                if ($club == null || $club == '') {
                  $clubValidation = true;
                  return false;
                }
                if (trim($sheet['team']) == null || trim($sheet['team']) == '') {
                  $teamValidation = true;
                  return false;
                }
                if (trim($sheet['place']) == null || trim($sheet['place']) == '') {
                  $placeValidation = true;
                  return false;
                }
                if (trim($sheet['agecategory']) == null || trim($sheet['place']) == '') {
                  $ageCategoryValidation = true;
                  return false;
                }
                $sheet['categoryname'] = ($sheet['categoryname'] == '') ? $sheet['agecategory'] : $sheet['categoryname'];
                $ageCategory = trim($sheet['agecategory']);
                $categoryName = trim($sheet['categoryname']);
                $ageCategoryId = null;
                if($ageCategory != '' && $categoryName != '') {
                  $toProcessTeam = true;

                  $notToProcessAgeCategory = array_filter($furtherNotToProcessAgeCategories, function($category) use($ageCategory, $categoryName){
                    return $ageCategory == $category['ageCategory'] && strtolower($categoryName) == strtolower($category['categoryName']);
                  });

                  $notToProcessNonExistingAgeCategory = array_filter($nonExistingAgeCategories, function($category) use($ageCategory, $categoryName){
                    return $ageCategory == $category['ageCategory'] && strtolower($categoryName) == strtolower($category['categoryName']);
                  });

                  if(count($notToProcessAgeCategory) > 0 || count($notToProcessNonExistingAgeCategory) > 0) {
                    $toProcessTeam = false;
                  }

                  if($toProcessTeam) {
                    $matchingAgeCategory = array_filter($allAgeCategories, function($category) use($ageCategory, $categoryName){
                      return $ageCategory == $category['category_age'] && strtolower($categoryName) == strtolower($category['group_name']);
                    });

                    if(count($matchingAgeCategory) === 0) {
                      $nonExistingAgeCategories[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                    } else {
                      $ageCategoryId = current($matchingAgeCategory)['id'];
                    }

                    if($ageCategoryId) {
                      $teamExist = array_filter($alreadyUploadedTeams, function($team) use($sheet) {
                        return ($team['esr_reference'] == $sheet['teamid']);
                      });
                      $resultMatchingAgeCategory = array_filter($resultEnteredAgeCategories, function($category) use($ageCategory, $categoryName){
                        return $ageCategory == $category['category_age'] && strtolower($categoryName) == strtolower($category['category_age']);
                      });
                      if(count($resultMatchingAgeCategory) > 0) {
                        $notProcessedAgeCategoriesDueToResultEntered[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                      }
                      if((count($teamExist) > 0 && $ageCategoryId != current($teamExist)['age_group_id'])) {
                        $teamsInDifferentAgeCategory[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                      }
                      if(isset($alreadyUploadedTeamsByAgeCategory[$ageCategoryId]) && count($teamExist) == 0) {
                        $teamsNotUploadedOfAgeCategory[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                      }
                      $teamExistInUploadSheet = array_filter($allTeamsInSheet, function($team) use($sheet) {
                        return ($team['teamid'] == $sheet['teamid']);
                      });
                      $allTeams[$ageCategoryId][] = $sheet;
                      $allTeamsInSheet[] = $sheet;
                      if(count($teamExistInUploadSheet) > 0)
                      {
                        $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                        foreach($teamExistInUploadSheet as $team) {
                          $ageCategoryDetail = array_filter($allAgeCategories, function($category) use($team){
                            return trim($team['agecategory']) == $category['category_age'] && strtolower(trim($team['categoryname'])) == strtolower($category['group_name']);
                          });
                          if(count($ageCategoryDetail) > 0) {
                            if(!isset($furtherNotToProcessAgeCategories[current($ageCategoryDetail)['id']])) {
                              $furtherNotToProcessAgeCategories[current($ageCategoryDetail)['id']] = ['ageCategory' => trim($team['agecategory']), 'categoryName' => trim($team['categoryname'])];
                            }
                            $checkIfAlreadyExist = array_filter($notProcessedAgeCategoriesDuetoSameTeamInUploadSheet, function($category) use($team){
                              return trim($team['agecategory']) == $category['ageCategory'] && strtolower(trim($team['categoryname'])) == strtolower($category['categoryName']);
                            });
                            if(count($checkIfAlreadyExist) === 0) {
                              $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet[] = ['ageCategory' => trim($team['agecategory']), 'categoryName' => trim($team['categoryname'])];
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            });
        }, 'ISO-8859-1');
        if ($clubValidation) {
          return ['status_code' => '422', 'message' => 'Please upload a sheet with valid club data.'];
        }
        if ($teamValidation) {
          return ['status_code' => '422', 'message' => 'Please upload a sheet with valid team data.'];
        }
        if ($placeValidation) {
          return ['status_code' => '422', 'message' => 'Please upload a sheet with valid place data.'];
        }
        if ($ageCategoryValidation) {
          return ['status_code' => '422', 'message' => 'Please upload a sheet with valid age category data.'];
        }
        foreach($allTeams as $ageCategoryId=>$ageCategoryTeams) {
          $matchingAgeCategory = array_filter($allAgeCategories, function($category) use($ageCategoryId){
            return $ageCategoryId == $category['id'];
          });
          $totalTeams = current($matchingAgeCategory)['total_teams'];
          if(isset($furtherNotToProcessAgeCategories[$ageCategoryId])) {
            continue;
          }
          if($totalTeams !== count($ageCategoryTeams)) {
            $teamNotMatchingAgeCategories[] = ['ageCategory' => current($matchingAgeCategory)['category_age'], 'categoryName' => current($matchingAgeCategory)['group_name']];
            continue;
          }
          foreach($ageCategoryTeams as $team) {
            $this->teamObj->create($team, $teamData['tournamentId']);
          }
        }

        return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted', 'teamNotMatchingAgeCategories' => $teamNotMatchingAgeCategories, 'nonExistingAgeCategories' => $nonExistingAgeCategories, 'teamsNotUploadedOfAgeCategory' => $teamsNotUploadedOfAgeCategory, 'teamsInDifferentAgeCategory' => $teamsInDifferentAgeCategory, 'notProcessedAgeCategoriesDueToResultEntered' => $notProcessedAgeCategoriesDueToResultEntered, 'notProcessedAgeCategoriesDuetoSameTeamInUploadSheet' => $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet];
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

        $signedUrl = UrlSigner::sign(secure_url('api/teams/getTeamsFairPlayData/report/reportExport?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

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
      $signedUrl = UrlSigner::sign(secure_url('api/teams/getGroupsViewData/report/print?' . $groupsViewData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

      return $signedUrl;
    }

    public function printGroupsViewReport(Request $request)
    {
      return $this->teamObj->printGroupsViewReport($request->all());
    }

    public function allocateTeamsAutomatically(Request $request)
    {
      return $this->teamObj->allocateTeamsAutomatically($request->all());
    }
}
