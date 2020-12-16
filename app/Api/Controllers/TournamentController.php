<?php
namespace Laraspace\Api\Controllers;

use UrlSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laraspace\Models\Team;
use Laraspace\Models\Referee;
use Laraspace\Models\Position;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Http\Requests\Tournament\DeleteRequest;
use Laraspace\Http\Requests\Tournament\PublishRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laraspace\Http\Requests\Tournament\TemplatesRequest;
use Laraspace\Http\Requests\Tournament\TournamentSummary;
use Laraspace\Http\Requests\Tournament\StoreUpdateRequest;
use Laraspace\Http\Requests\Tournament\GetTemplateRequest;
use Laraspace\Http\Requests\Tournament\SaveSettingsRequest;
use Laraspace\Http\Requests\Tournament\TournamentClubRequest;
use Laraspace\Http\Requests\Tournament\GenerateReportRequest;
use Laraspace\Http\Requests\Tournament\StoreBasicDetailRequest;
use Laraspace\Http\Requests\Tournament\TournamentFilterRequest;
use Laraspace\Http\Requests\Tournament\GetTournamentBySlugRequest;
use Laraspace\Http\Requests\Tournament\DuplicateTournamentRequest;
use Laraspace\Http\Requests\Tournament\GetCategoryCompetitionsRequest;
use Laraspace\Http\Requests\Tournament\CategoryCompetitionColorRequest;
use Laraspace\Http\Requests\Tournament\GetAllPublishedTournamentsRequest;
use Laraspace\Http\Requests\Tournament\GetSignedUrlForTournamentReportRequest;
use Laraspace\Http\Requests\Tournament\GetUserLoginFavouriteTournamentRequest;
use Laraspace\Http\Requests\Tournament\GetSignedUrlForTournamentReportExportRequest;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\TournamentContract;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 * @Author Knayak@aecordigital.com
 */
class TournamentController extends BaseController
{
    use ValidatesRequests;

    /**
     * @param object $tournamentObj
     */
    public function __construct(TournamentContract $tournamentObj)
    {
        $this->tournamentObj = $tournamentObj;
    }

    /**
     * Show all Tournament Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/tournament")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function index()
    {
        return $this->tournamentObj->index();
    }

    /**
     * Show all Tournament Details By Status.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/tournament/status")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getTournamentByStatus(Request $request)
    {
        return $this->tournamentObj->getTournamentByStatus($request);
    }

    public function getTournamentBySlug(GetTournamentBySlugRequest $request, $slug)
    {
        return $this->tournamentObj->getTournamentBySlug($slug);
    }

    /**
     * Show all Tournament Templates.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/templates")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "json": "foo"})
     */
    public function templates(TemplatesRequest $request)
    {
        return $this->tournamentObj->templates($request->all());
    }

    /**
     * Show json Data for Template.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/templates")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "json": "foo"})
     */
    public function getTemplate(GetTemplateRequest $request)
    {
        return $this->tournamentObj->getTemplate($request->all());
    }

    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/tournament/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function create(StoreUpdateRequest $request)
    {
        return $this->tournamentObj->create($request);
    }

    /**
     * Edit  Torunament.
     *
     * @Post("/tournament/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->tournamentObj->edit($request);
    }

    /**
     * Delete  Torunament.
     *
     * @Post("/tournament/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(DeleteRequest $request, $id)
    {
        return $this->tournamentObj->delete($id);
    }
    public function tournamentSummary(TournamentSummary $request)
    {
        return $this->tournamentObj->tournamentSummary($request);
    }
    public function exportReport(Request $request) {
       return $this->tournamentObj->generateReport($request->all());
    }
    public function generateReport(GenerateReportRequest $request) {
       return $this->tournamentObj->generateReport($request->all());
    }
    public function generatePrint(Request $request) {

        // dd($this->tournamentObj->generatePrint($request->all()));
       return $this->tournamentObj->generatePrint($request->all());
    }
    protected function formatValidationErrors(\Illuminate\Contracts\Validation\Validator $validator)
    {
        logger($validator->errors()->all());
        return $validator->errors()->all();
    }
    public function updateStatus(PublishRequest $request) {
       return $this->tournamentObj->updateStatus($request->all());
    }
    public function tournamentFilter(TournamentFilterRequest $request)
    {
      return $this->tournamentObj->tournamentFilter($request->all());
    }
    public function getAllCategory(Request $request)
    {
      return $this->tournamentObj->getAllCategory($request->all());
    }
    public function getUserLoginDefaultTournament(Request $request)
    {
      return $this->tournamentObj->getUserLoginDefaultTournament($request->all());
    }
     public function getUserLoginFavouriteTournament(GetUserLoginFavouriteTournamentRequest $request)
    {
      return $this->tournamentObj->getUserLoginFavouriteTournament($request->all());
    }
    public function getTournamentClub(TournamentClubRequest $request)
    {
      return $this->tournamentObj->getTournamentClub($request->all());
    }
    public function addTournamentDetails(StoreBasicDetailRequest $request)
    {
        return $this->tournamentObj->addTournamentDetails($request->all());
    }
    public function getCategoryCompetitions(GetCategoryCompetitionsRequest $request)
    {
        return $this->tournamentObj->getCategoryCompetitions($request->all());
    }

    public function getAllPublishedTournaments(GetAllPublishedTournamentsRequest $request) {
        return $this->tournamentObj->getAllPublishedTournaments($request->all());
    }

    public function getFilterDropDownData(Request $request) {
      return $this->tournamentObj->getFilterDropDownData($request->all());
    }

    public function getSignedUrlForTournamentReport(GetSignedUrlForTournamentReportRequest $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData  = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(url('api/tournament/report/print?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getSignedUrlForTournamentReportExport(GetSignedUrlForTournamentReportExportRequest $request)
    {
        $reportData = $request->all();
        ksort($reportData);
        $reportData  = http_build_query($reportData);

        $signedUrl = UrlSigner::sign(secure_url('api/tournament/report/reportExport?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    public function getCompetitionAndPitchDetail(Request $request)
    {
        return $this->tournamentObj->getCompetitionAndPitchDetail($request->all());   
    }

    public function scheduleAutomaticPitchPlanning(Request $request)
    {
        return $this->tournamentObj->scheduleAutomaticPitchPlanning($request->all());   
    }

    public function getAllPitchesWithDays(Request $request, $pitchId)
    {
        return $this->tournamentObj->getAllPitchesWithDays($pitchId);      
    }

    /**
     * Update competition display name
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function updateCompetitionDisplayName(Request $request)
    {
        return $this->tournamentObj->updateCompetitionDisplayName($request->all());
    }
    /**
     * Update category division display name.
    */
    public function updateCategoryDivisionName(Request $request)
    {
        return $this->tournamentObj->updateCategoryDivisionName($request->all());
    }

    public function duplicateTournament(DuplicateTournamentRequest $request)
    {
        return $this->tournamentObj->duplicateTournament($request->all());
    }

    public function duplicateTournamentList(Request $request)
    {
        return $this->tournamentObj->duplicateTournamentList($request->all());
    }

    public function duplicateExistingTournament(Request $request)   
    {   
        $oldTournamentId = $request->old_tournament_id; 
        $newTournamentId = $request->new_tournament_id; 
        $teamsMappingArray = [];   
        $ageCategoriesMappingArray = [];    
        $refereeNewAgeCategoriesArray = []; 
        $duplicateTournaments = Tournament::where('id', $newTournamentId)->get();  
        $existingTeams = Team::where('tournament_id', $oldTournamentId)->get();    
        $existingTeamsIdsArray = $existingTeams->pluck('id');   
        $duplicatedTeams = Team::where('tournament_id', $newTournamentId)->get()->toArray();    
            
        $existingAgeCategories = TournamentCompetationTemplates::where('tournament_id', $oldTournamentId)->get()->pluck('id');  
        $duplicateAgeCategories = TournamentCompetationTemplates::where('tournament_id', $newTournamentId)->get()->toArray();   
        $existingTournamentFixtures = TempFixture::where('tournament_id', $oldTournamentId)->get();    
        $duplicatedTournamentFixtures = TempFixture::where('tournament_id', $newTournamentId)->get();   
        $existingReferees = Referee::where('tournament_id', $oldTournamentId)->get();  
        $duplicatedReferees = Referee::where('tournament_id', $newTournamentId)->get(); 
         // preparing teams mapping array   
        foreach ($duplicatedTeams as $key => $team) {               
            $teamsMappingArray[$existingTeamsIdsArray[$key]] = $team['id']; 
        }   
        foreach ($duplicateAgeCategories as $ageCategorykey => $ageCategory) { 
            $ageCategoriesMappingArray[$existingAgeCategories[$ageCategorykey]] = $ageCategory['id'];   
        }   
         // temp fixture fields updation    
        foreach ($duplicatedTournamentFixtures as $key => $tempFixture) {   
            $tempFixture->update([  
                'match_winner' => isset($teamsMappingArray[$tempFixture->match_winner]) ? $teamsMappingArray[$tempFixture->match_winner] : null,    
                'home_team' =>  isset($teamsMappingArray[$tempFixture->home_team]) ? $teamsMappingArray[$tempFixture->home_team] : 0,   
                'away_team' =>  isset($teamsMappingArray[$tempFixture->away_team]) ? $teamsMappingArray[$tempFixture->away_team] : 0,   
            ]); 
        }   
         // referees fields updation    
        foreach ($existingReferees as $referee) {   
            $refereeNewAgeCategoriesArray = [];
            if($referee->age_group_id != null) {
                $explodedExistingRefereeAgeCategories = explode(",", $referee->age_group_id);   
                foreach ($explodedExistingRefereeAgeCategories as $key => $ageCategory) {  
                    if(isset($ageCategoriesMappingArray[$ageCategory])) {
                        $refereeNewAgeCategoriesArray[] = $ageCategoriesMappingArray[$ageCategory]; 
                    }
                }   
            }   
            $referee->update([ 
                'age_group_id' => ($referee->age_group_id != null || count($refereeNewAgeCategoriesArray) > 0) ? implode(",", $refereeNewAgeCategoriesArray) : null,
            ]); 
        }   
         // positions fields updation   
        if($ageCategoriesMappingArray) {    
            foreach ($ageCategoriesMappingArray as $key => $ageCategory) {  
                $positions = Position::where('age_category_id', $key)->get();   
                foreach ($positions as $position) { 
                    if($position->team_id) {    
                        $position->update([ 
                            'team_id' => isset($teamsMappingArray[$position->team_id]) ? $teamsMappingArray[$position->team_id] : null, 
                        ]); 
                    }   
                }   
            }   
        }   
         echo "<pre>";print_r('done');echo "</pre>";exit;   
    }

    public function saveSettings(SaveSettingsRequest $request)
    {
        return $this->tournamentObj->saveSettings($request->all());
    }

    public function saveContactDetails(Request $request)
    {
        return $this->tournamentObj->saveContactDetails($request->all());
    }

    public function saveVenueDetails(Request $request)
    {
        return $this->tournamentObj->saveVenueDetails($request->all());
    }

    public function getPresentationSettings(Request $request, $tournamentId)
    {
        $tournament = Tournament::find($tournamentId);
        $ageCategoryIds = TempFixture::where('tournament_id', $tournament->id)
                                    ->whereDate('match_datetime', date('Y-m-d'))
                                    // ->whereDate('match_datetime', date('2020-05-06'))
                                    ->orderBy('match_datetime', 'ASC')
                                    ->pluck('age_group_id')
                                    ->unique()->values()->all();

        return  [
                    'screen_rotate_time_in_seconds' => $tournament->screen_rotate_time_in_seconds,
                    'show_presentation' => count($ageCategoryIds) > 0 ? true : false,
                ];
    }
}
