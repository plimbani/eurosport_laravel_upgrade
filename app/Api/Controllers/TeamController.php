<?php

namespace App\Api\Controllers;

use App\Api\Contracts\TeamContract;
use App\Http\Requests\Team\AllClubsRequest;
use App\Http\Requests\Team\AllCountriesRequest;
use App\Http\Requests\Team\AllTeamColorsRequest;
use App\Http\Requests\Team\AssignTeamRequest;
use App\Http\Requests\Team\ChangeTeamNameRequest;
use App\Http\Requests\Team\CheckTeamExistRequest;
use App\Http\Requests\Team\ClubsTeamsRequest;
use App\Http\Requests\Team\GetAllCompetitionTeamsFromFixtureRequest;
use App\Http\Requests\Team\GetAllTournamentTeamsRequest;
use App\Http\Requests\Team\GetSignedUrlForGroupsViewReportRequest;
use App\Http\Requests\Team\GetSignedUrlForTeamsFairPlayReportExport;
use App\Http\Requests\Team\GetSignedUrlForTeamsFairPlayReportPrint;
use App\Http\Requests\Team\GetTeamsRequest;
use App\Http\Requests\Team\GetTournamentTeamDetailsRequest;
use App\Http\Requests\Team\ResetAllTeamsRequest;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\TeamDetailsRequest;
use App\Http\Requests\Team\TeamsListRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Models\Team;
use App\Models\TempFixture;
use App\Models\TournamentCompetationTemplates;
use Carbon\Carbon;
use Illuminate\Http\Request;
// Need to Define Only Contracts
use UrlSigner;
use App\Imports\TeamsImport;

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
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getTeams(GetTeamsRequest $request)
    {
        return $result = $this->teamObj->getTeams($request);
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
     *
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


      $import = \Excel::toArray(new TeamsImport, $file);

        // Define an array to hold any validation errors.
        $validationErrors = [];
        $sheets=$import[0];
           foreach ($sheets as $sheet) {
                // Validate required fields.
                if (empty(trim($sheet['club']))) {
                    $validationErrors['club'] = 'Please upload a sheet with valid club data.';
                    break; // Exit both foreach loops.
                }

                if (empty(trim($sheet['team']))) {
                    $validationErrors['team'] = 'Please upload a sheet with valid team data.';
                    break; // Exit both foreach loops.
                }

                // Additional validations based on the 'current_layout' configuration.
                if (config('config-variables.current_layout') === 'tmp') {
                    if (empty(trim($sheet['place'])) || empty(trim($sheet['agecategory']))) {
                        $validationErrors['place_agecategory'] = 'Please upload a sheet with valid place and age category data.';
                        break; // Exit both foreach loops.
                    }
              
                     $sheet['categoryname'] = ($sheet['categoryname'] == '') ? $sheet['agecategory'] : $sheet['categoryname'];

                        $ageCategory = trim($sheet['agecategory']);
                        $categoryName = trim($sheet['categoryname']);
                        $ageCategoryId = null;
                        if ($ageCategory != '' && $categoryName != '') {
                            $toProcessTeam = true;

                            $notToProcessAgeCategory = array_filter($furtherNotToProcessAgeCategories, function ($category) use ($ageCategory, $categoryName) {
                                return $ageCategory == $category['ageCategory'] && strtolower($categoryName) == strtolower($category['categoryName']);
                            });

                            $notToProcessNonExistingAgeCategory = array_filter($nonExistingAgeCategories, function ($category) use ($ageCategory, $categoryName) {
                                return $ageCategory == $category['ageCategory'] && strtolower($categoryName) == strtolower($category['categoryName']);
                            });

                            if (count($notToProcessAgeCategory) > 0 || count($notToProcessNonExistingAgeCategory) > 0) {
                                $toProcessTeam = false;
                            }

                            if ($toProcessTeam) {
                                $matchingAgeCategory = array_filter($allAgeCategories, function ($category) use ($ageCategory, $categoryName) {
                                    return $ageCategory == $category['category_age'] && strtolower($categoryName) == strtolower($category['group_name']);
                                });

                                if (count($matchingAgeCategory) === 0) {
                                    $nonExistingAgeCategories[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                } else {
                                    $ageCategoryId = current($matchingAgeCategory)['id'];
                                }

                                if ($ageCategoryId) {
                                    $teamExist = array_filter($alreadyUploadedTeams, function ($team) use ($sheet) {
                                        return $team['esr_reference'] == $sheet['teamid'];
                                    });
                                    $resultMatchingAgeCategory = array_filter($resultEnteredAgeCategories, function ($category) use ($ageCategory, $categoryName) {
                                        return $ageCategory == $category['category_age'] && strtolower($categoryName) == strtolower($category['category_age']);
                                    });
                                    if (count($resultMatchingAgeCategory) > 0) {
                                        $notProcessedAgeCategoriesDueToResultEntered[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                    }
                                    if ((count($teamExist) > 0 && $ageCategoryId != current($teamExist)['age_group_id'])) {
                                        $teamsInDifferentAgeCategory[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                    }
                                    if (isset($alreadyUploadedTeamsByAgeCategory[$ageCategoryId]) && count($teamExist) == 0) {
                                        $teamsNotUploadedOfAgeCategory[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                    }
                                    $teamExistInUploadSheet = array_filter($allTeamsInSheet, function ($team) use ($sheet) {
                                        return $team['teamid'] == $sheet['teamid'];
                                    });
                                    $allTeams[$ageCategoryId][] = $sheet;
                                    $allTeamsInSheet[] = $sheet;
                                    if (count($teamExistInUploadSheet) > 0) {
                                        $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet[] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory, 'categoryName' => $categoryName];
                                        foreach ($teamExistInUploadSheet as $team) {
                                            $ageCategoryDetail = array_filter($allAgeCategories, function ($category) use ($team) {
                                                return trim($team['agecategory']) == $category['category_age'] && strtolower(trim($team['categoryname'])) == strtolower($category['group_name']);
                                            });
                                            if (count($ageCategoryDetail) > 0) {
                                                if (! isset($furtherNotToProcessAgeCategories[current($ageCategoryDetail)['id']])) {
                                                    $furtherNotToProcessAgeCategories[current($ageCategoryDetail)['id']] = ['ageCategory' => trim($team['agecategory']), 'categoryName' => trim($team['categoryname'])];
                                                }
                                                $checkIfAlreadyExist = array_filter($notProcessedAgeCategoriesDuetoSameTeamInUploadSheet, function ($category) use ($team) {
                                                    return trim($team['agecategory']) == $category['ageCategory'] && strtolower(trim($team['categoryname'])) == strtolower($category['categoryName']);
                                                });
                                                if (count($checkIfAlreadyExist) === 0) {
                                                    $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet[] = ['ageCategory' => trim($team['agecategory']), 'categoryName' => trim($team['categoryname'])];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    } else {
                       
                        if (trim($sheet['agecategory']) == null) {
                            $ageCategoryValidation = true;

                            return false;
                        }
                        $sheet['categoryname'] = ($sheet['agecategory'] == '') ? $sheet['agecategory'] : '';

                        $ageCategory = trim($sheet['agecategory']);
                        $ageCategoryId = null;
                        if ($ageCategory != '') {
                            $toProcessTeam = true;

                            $notToProcessAgeCategory = array_filter($furtherNotToProcessAgeCategories, function ($category) use ($ageCategory) {
                                return $ageCategory == $category['ageCategory'];
                            });

                            $notToProcessNonExistingAgeCategory = array_filter($nonExistingAgeCategories, function ($category) use ($ageCategory) {
                                return $ageCategory == $category['ageCategory'];
                            });

                            if (count($notToProcessAgeCategory) > 0 || count($notToProcessNonExistingAgeCategory) > 0) {
                                $toProcessTeam = false;
                            }

                            if ($toProcessTeam) {

                                $matchingAgeCategory = array_filter($allAgeCategories, function ($category) use ($ageCategory) {
                                     $ageCategory == $category['category_age'];
                                });

                                if (count($matchingAgeCategory) === 0) {
                                    $nonExistingAgeCategories[] = ['ageCategory' => $ageCategory];
                                } else {
                                    $ageCategoryId = current($matchingAgeCategory)['id'];
                                }

                                if ($ageCategoryId) {
                                    $resultMatchingAgeCategory = array_filter($resultEnteredAgeCategories, function ($category) use ($ageCategory) {
                                        return $ageCategory == $category['category_age'];
                                    });
                                    if (count($resultMatchingAgeCategory) > 0) {
                                        $notProcessedAgeCategoriesDueToResultEntered[] = ['ageCategory' => $ageCategory];
                                        $furtherNotToProcessAgeCategories[$ageCategoryId] = ['ageCategory' => $ageCategory];
                                    }

                                    $allTeams[$ageCategoryId][] = $sheet;
                                    $allTeamsInSheet[] = $sheet;
                                }
                            }
                        }

                    }
                // Processing the sheet data...
                // This is where you'd add the logic to handle each sheet's data.
            }
        
          
        // Check if there were any validation errors.
        if (!empty($validationErrors)) {
            return ['status_code' => '422', 'message' => $validationErrors];
        }

        // Add logic for further processing if validation is successful.
        // This could involve organizing the data, saving it to the database, etc.

        // If everything is successful, you can return a success message or status.
       // dd($allTeams);
          foreach ($allTeams as $ageCategoryId => $ageCategoryTeams) {

                $matchingAgeCategory = array_filter($allAgeCategories, function ($category) use ($ageCategoryId) {
                    return $ageCategoryId == $category['id'];
                });
                $totalTeams = current($matchingAgeCategory)['total_teams'];
                if (isset($furtherNotToProcessAgeCategories[$ageCategoryId])) {
                    continue;
                }
                if ($totalTeams !== count($ageCategoryTeams)) {
                    $teamNotMatchingAgeCategories[] = ['ageCategory' => current($matchingAgeCategory)['category_age'], 'categoryName' => current($matchingAgeCategory)['group_name']];

                    continue;
                }

                if (config('config-variables.current_layout') === 'commercialisation') {
                    // remove teams if alreay exist in same age category
                    $requestData = new \Illuminate\Http\Request();
                    $requestData->replace([
                        'ageCategoryId' => $ageCategoryId,
                        'tournamentId' => $teamData['tournamentId'],
                    ]);
                    $this->teamObj->resetAllTeams($requestData);
                }
                 foreach($ageCategoryTeams as $team) {
                    $this->teamObj->create($team, $teamData['tournamentId']);
                  }
          }  
        return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted', 'teamNotMatchingAgeCategories' => $teamNotMatchingAgeCategories, 'nonExistingAgeCategories' => $nonExistingAgeCategories, 'teamsNotUploadedOfAgeCategory' => $teamsNotUploadedOfAgeCategory, 'teamsInDifferentAgeCategory' => $teamsInDifferentAgeCategory, 'notProcessedAgeCategoriesDueToResultEntered' => $notProcessedAgeCategoriesDueToResultEntered, 'notProcessedAgeCategoriesDuetoSameTeamInUploadSheet' => $notProcessedAgeCategoriesDuetoSameTeamInUploadSheet];
    }

    public function assignTeam(AssignTeamRequest $request)
    {
        return $this->teamObj->assignTeams($request->all());
    }

    public function getAllTeamsGroup(Request $request)
    {
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
     *
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
     *
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
        $reportData = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(secure_url('api/teams/getTeamsFairPlayData/report/reportExport?'.$reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getSignedUrlForFairPlayReportPrint(GetSignedUrlForTeamsFairPlayReportPrint $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(url('api/teams/getTeamsFairPlayData/report/print?'.$reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

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
        $groupsViewData = http_build_query($groupsViewData);
        $signedUrl = UrlSigner::sign(secure_url('api/teams/getGroupsViewData/report/print?'.$groupsViewData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

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
