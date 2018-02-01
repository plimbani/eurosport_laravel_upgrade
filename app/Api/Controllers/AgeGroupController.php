<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\AgeGroupContract;
/**
 * Age Group Resource Description.
 *
 * @Resource("age_group")
 *
 * @Author Knayak@aecordigital.com
 */
class AgeGroupController extends BaseController
{
    public function __construct(AgeGroupContract $ageGroupObj)
    {
        $this->ageGroupObj = $ageGroupObj;
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

    /**
     * Add  Age Group.
     *
     * @Post("/age_group/createCompeationFormat")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createCompetationFomat(Request $request)
    {
        return $this->ageGroupObj->createCompetationFomat($request->all());
    }
    public function getCompetationFormat(Request $request) {
        return $this->ageGroupObj->GetCompetationFormat($request);
    }
    public function deleteCompetationFormat(Request $request) {
       return $this->ageGroupObj->deleteCompetationFormat($request);
    }

    public function getPlacingsData(Request $request) {
        return $this->ageGroupObj->getPlacingsData($request->all());
    }

}
