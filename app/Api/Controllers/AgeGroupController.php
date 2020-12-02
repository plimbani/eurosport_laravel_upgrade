<?php

namespace Laraspace\Api\Controllers;

use UrlSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laraspace\Models\Position;
use Laraspace\Http\Requests\AgeGroup\TeamDetailsRequest;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;
use Laraspace\Http\Requests\AgeGroup\GetCompetationFormatRequest;
use Laraspace\Http\Requests\AgeGroup\CreateCompetationFomatRequest;
use Laraspace\Http\Requests\AgeGroup\DeleteCompetitionFormatRequest;
use Laraspace\Http\Requests\AgeGroup\GetSignedUrlForMatchSchedulePrintRequest;

/**
 * Age Group Resource Description.
 *
 * @Resource("age_group")
 *
 * @Author Knayak@aecordigital.com
 */
class AgeGroupController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * Show all Tournament Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/age_group")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function index()
    {
        return $this->ageGroupObj->index();
    }

    /**
     * Create  Torunament.
     *
     * Create New Age Group
     *
     * @Post("/age_group/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function create(Request $request)
    {
        return $this->ageGroupObj->create($request);
    }

    /**
     * Edit  Age Group.
     *
     * @Post("/age_group/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->ageGroupObj->edit($request);
    }

    /**
     * Delete  Age Group.
     *
     * @Post("/age_group/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(Request $request)
    {
        return $this->ageGroupObj->delete($request);
    }

    public function ageCategoryData(Request $request)
    {
        return $this->ageGroupObj->ageCategoryData($request);
    }
    /**
     * Add  Age Group.
     *
     * @Post("/age_group/createCompeationFormat")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createCompetationFomat(CreateCompetationFomatRequest $request)
    {
        return $this->ageGroupObj->createCompetationFomat($request->all());
    }
    public function getCompetationFormat(GetCompetationFormatRequest $request) {
        $client = new HttpClient();
        $competationFormat = $client->post('/age_group/getCompetationFormat', [], $request->all());
        return $competationFormat;
    }
    public function deleteCompetationFormat(DeleteCompetitionFormatRequest $request) {
       return $this->ageGroupObj->deleteCompetationFormat($request);
    }

    public function getPlacingsData(TeamDetailsRequest $request) {
        $client = new HttpClient();
        $placingsData = $client->post('/age_group/getPlacingsData', [], $request->all());
        return $placingsData;
    }

    public function copyAgeCategory(Request $request) {
        return $this->ageGroupObj->copyAgeCategory($request->all());
    }

    public function viewTemplateGraphicImage(Request $request) {
        return $this->ageGroupObj->viewTemplateGraphicImage($request->all());
    }

    public function deleteFinalPlacingTeam(Request $request) {
        return $this->ageGroupObj->deleteFinalPlacingTeam($request->all());
    }

    public function getSignedUrlForMatchSchedulePrint(GetSignedUrlForMatchSchedulePrintRequest $request)
    {
        $client = new HttpClient();
        $placingsData = $client->post('/getSignedUrlForMatchSchedulePrint', [], $request->all());
        return $placingsData;
    }

    public function generateMatchSchedulePrint(Request $request)
    {
        return $this->ageGroupObj->generateMatchSchedulePrint($request->all());
    }
}
