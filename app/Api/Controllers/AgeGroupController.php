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

    /**
     * Create  team.
     *
     * Create New Team
     *
     * @Post("/age_group/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createAgeGroup(Request $request)
    {
        return $this->ageGroupObj->createAgeGroup($request);
    }
}
