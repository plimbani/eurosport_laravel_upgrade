<?php

namespace Laraspace\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Contracts\AgeGroupContract;

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
     *
     * @param mixed $ageId
     */
    public function edit(Request $request, $ageId)
    {
        return $this->ageGroupObj->edit($request, $ageId);
    }

    /**
     * Delete  Age Group.
     *
     * @Post("/age_group/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     *
     * @param mixed $deleteId
     */
    public function delete($deleteId)
    {
        return $this->ageGroupObj->delete($deleteId);
    }
}
