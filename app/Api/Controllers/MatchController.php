<?php

namespace Laraspace\Api\Controllers;

// use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\MatchContract;
use JWTAuth;

/**
 * Matches Resource Description.
 *
 * @Resource("Matches")
 *
 * @Author Kparikh@aecordigital.com
 */
class MatchController extends BaseController
{
    public function __construct(MatchContract $matchObj)
    {

        $this->matchObj = $matchObj;
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Matches.
     *
     * @Get("/matches")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getMatches()
    {

        return $this->matchObj->getAllMatches();
    }

    /**
     * Create New Match Result.
     *
     * @Post("/match/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createMatch(Request $request)
    {
        return $this->matchObj->createMatch($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/match/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->matchObj->edit($request);
    }

    public function deleteMatch($deleteId)
    {
        return $this->matchObj->deleteMatch($deleteId);
    }
    public function getDraws(Request $request){
        return $this->matchObj->getDraws($request);
    }
    public function getFixtures(Request $request){
        return $this->matchObj->getFixtures($request);
    }
    public function getStanding(Request $request){
        return $this->matchObj->getStanding($request);
    }
    public function getDrawTable(Request $request) {
        return $this->matchObj->getDrawTable($request);
    }
    public function scheduleMatch(Request $request) {
         return $this->matchObj->scheduleMatch($request);
    }
    public function unscheduleMatch(Request $request) {
        return $this->matchObj->unscheduleMatch($request);
    }

    public function getAllScheduledMatch(Request $request) {
        return $this->matchObj->getAllScheduledMatch($request);
    }
    public function getMatchDetail(Request $request)
    {
        return $this->matchObj->getMatchDetail($request);
    }
    public function generateMatchPrint(Request $request)
    {
        return $this->matchObj->generateMatchPrint($request->all());
    }
    public function removeAssignedReferee(Request $request)
    {
        return $this->matchObj->removeAssignedReferee($request);
    }
    public function assignReferee(Request $request)
    {
        return $this->matchObj->assignReferee($request);
    }
    public function saveResult(Request $request)
    {
        return $this->matchObj->saveResult($request);
    }
    public function saveUnavailableBlock(Request $request)
    {
        return $this->matchObj->saveUnavailableBlock($request);
    }
    public function getUnavailableBlock(Request $request)
    {
        return $this->matchObj->getUnavailableBlock($request);
    }
    public function removeBlock($blockId)
    {
        return $this->matchObj->removeBlock($blockId);
    }
    public function updateScore(Request $request)
    {
        return $this->matchObj->updateScore($request);
    }
}
