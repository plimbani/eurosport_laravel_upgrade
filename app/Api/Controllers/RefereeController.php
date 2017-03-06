<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\RefereeContract;

/**
 * Referees Resource Description.
 *
 * @Resource("Referees")
 *
 * @Author Kparikh@aecordigital.com
 */
class RefereeController extends BaseController
{
    public function __construct(RefereeContract $refereeObj)
    {
        $this->refereeObj = $refereeObj;
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Referee Details.
     *
     * Get a JSON representation of all the Referee.
     *
     * @Get("/referee")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getReferees()
    {
        return $this->refereeObj->getAllReferees();
    }

    /**
     * Create  referee.
     *
     * Create New Referee
     *
     * @Post("/referee/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createReferee(Request $request)
    {
        return $this->refereeObj->createReferee($request);
    }

    /**
     * Edit  referee.
     *
     * @Post("/referee/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request, $refereeId)
    {
        return $this->refereeObj->edit($request, $refereeId);
    }

    public function deleteReferee($deleteId)
    {
        return $this->refereeObj->deleteReferee($deleteId);
    }
}
