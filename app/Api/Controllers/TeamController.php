<?php

namespace App\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use App\Api\Contracts\TeamContract;

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

        $this->middleware('jwt.auth');
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
    public function index()
    {
        return $this->teamObj->index();
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
    public function create(Request $request)
    {
        return $this->teamObj->create($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/team/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     *
     * @param mixed $id
     * @param mixed $teamId
     */
    public function edit(Request $request, $teamId)
    {
        return $this->teamObj->edit($request, $teamId);
    }

    public function deleteTeam($deleteId)
    {
        return $this->teamObj->deleteTeam($deleteId);
    }
}
