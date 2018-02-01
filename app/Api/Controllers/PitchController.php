<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\PitchContract;
use JWTAuth;

/**
 * Matches Resource Description.
 *
 * @Resource("Matches")
 *
 * @Author Kparikh@aecordigital.com
 */
class PitchController extends BaseController
{
    public function __construct(PitchContract $pitchObj)
    {
        $this->pitchObj = $pitchObj;
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/pitches")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getPitches($tournamentId)
    {
        return $this->pitchObj->getAllPitches($tournamentId);
    }

    /**
     * Get pitch size summary
     *
     * Get a JSON representation of all the Pitches.
     *
     * @Get("/getPitchSizeWiseSummary")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getPitchSizeWiseSummary($tournamentId)
    {
        return $this->pitchObj->getPitchSizeWiseSummary($tournamentId);
    }

    /**
     * Create New Match Result.
     *
     * @Post("/match/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createPitch(Request $request)
    {
        return $this->pitchObj->createPitch($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/match/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request,$pitchId)
    {
        return $this->pitchObj->edit($request,$pitchId);
    }
    public function show($pitchId)
    {
        return $this->pitchObj->getPitchData($pitchId);
    }
    public function deletePitch($deleteId)
    {
        return $this->pitchObj->deletePitch($deleteId);
    }
}
