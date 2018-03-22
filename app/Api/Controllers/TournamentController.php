<?php
namespace Laraspace\Api\Controllers;

use UrlSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laraspace\Http\Requests\Tournament\DeleteRequest;
use Laraspace\Http\Requests\Tournament\PublishRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laraspace\Http\Requests\Tournament\TemplatesRequest;
use Laraspace\Http\Requests\Tournament\TournamentSummary;
use Laraspace\Http\Requests\Tournament\StoreUpdateRequest;
use Laraspace\Http\Requests\Tournament\GetTemplateRequest;
use Laraspace\Http\Requests\Tournament\TournamentClubRequest;
use Laraspace\Http\Requests\Tournament\GenerateReportRequest;
use Laraspace\Http\Requests\Tournament\StoreBasicDetailRequest;
use Laraspace\Http\Requests\Tournament\CategoryCompetitionColorRequest;
use Laraspace\Http\Requests\Tournament\GetSignedUrlForTournamentReportRequest;
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

    public function getTournamentBySlug(Request $request, $slug)
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
    public function tournamentFilter(Request $request)
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
     public function getUserLoginFavouriteTournament(Request $request)
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
    public function getCategoryCompetitions(Request $request)
    {
        return $this->tournamentObj->getCategoryCompetitions($request->all());
    }
    public function saveCategoryCompetitionColor(CategoryCompetitionColorRequest $request)
    {
        return $this->tournamentObj->saveCategoryCompetitionColor($request->all());
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

        $signedUrl = UrlSigner::sign(url('api/tournament/report/reportExport?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

}
