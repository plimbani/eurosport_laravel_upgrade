<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\RefereeContract;
use Laraspace\Api\Repositories\RefereeRepository;
/**
 * Referees Resource Description.
 *
 * @Resource("Referees")
 *
 * @Author Kparikh@aecordigital.com
 */
class RefereeController extends BaseController
{
    public function __construct(RefereeContract $refereeObj,RefereeRepository $refereeRepoObj)
    {
        $this->refereeObj = $refereeObj;
        $this->refereeRepoObj  =  $refereeRepoObj;
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
    public function getReferees(Request $request)
    {
        return $this->refereeObj->getAllReferees($request->all()['tournamentData']);
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
    public function updateReferee(Request $request)
    {
        $data = $request->all()['data'];
        // dd($data);

        return $this->refereeObj->edit($data, $data['refereeId']);
    }
    public function refereeDetail(Request $request)
    {
        return $this->refereeRepoObj->getRefereeFromId($request->refereeId);
        // dd($request->refereeId);
        // return $this->refereeObj->refereeDetail($request);

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
