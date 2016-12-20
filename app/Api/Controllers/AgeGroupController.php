<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use App\Api\Contracts\AgeGroupContract;

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
        $this->middleware('jwt.auth');
    }

    /**
     * Show all AgeGroup Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/age_group")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getAgeGroups()
    {
        return $this->ageGroupObj->getAllData();
    }

    public function create(Request $request)
    {
        return $this->ageGroupObj->create($request);
    }

    public function edit(Request $request, $ageId)
    {
        return $this->ageGroupObj->edit($request, $ageId);
    }

    public function delete($deleteId)
    {
        return $this->ageGroupObj->delete($deleteId);
    }
}
