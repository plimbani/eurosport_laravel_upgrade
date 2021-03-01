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
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;
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
    public function __construct()
    {
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
        $client = new HttpClient();
        $tournaments = $client->get('/tournaments', [], []);
        return $tournaments;
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
        $client = new HttpClient();
        $tournamentByStatus = $client->post('/tournaments/getTournamentByStatus', [], $request->all());
        return $tournamentByStatus;
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
    public function getCategoryCompetitions(Request $request)
    {
        $client = new HttpClient();
        $login = $client->login();
        $categoryCompetitions = $client->post('/tournament/getCategoryCompetitions', ['Authorization' => 'Bearer '.json_decode($login)->token], $request->all());
        return $categoryCompetitions;
    }

    public function getAllPublishedTournaments(GetAllPublishedTournamentsRequest $request) {
        $client = new HttpClient();
        $login = $client->login();
        $allPublishedTournaments = $client->get('/getAllPublishedTournaments', ['Authorization' => 'Bearer '.json_decode($login)->token]);
        return $allPublishedTournaments;
    }

    public function getFilterDropDownData(Request $request) {
        $client = new HttpClient();
        $login = $client->login();
        $filterDropDownData = $client->post('tournament/getFilterDropDownData', ['Authorization' => 'Bearer '.json_decode($login)->token], $request->all());
        return $filterDropDownData;
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
}
