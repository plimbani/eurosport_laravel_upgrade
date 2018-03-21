<?php

namespace Laraspace\Api\Controllers;

use JWTAuth;
use UrlSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Brotzka\DotenvEditor\DotenvEditor;
use Laraspace\Http\Requests\Pitch\ShowRequest;
use Laraspace\Http\Requests\Pitch\StoreRequest;
use Laraspace\Http\Requests\Pitch\UpdateRequest;
use Laraspace\Http\Requests\Pitch\DeleteRequest;
use Laraspace\Http\Requests\Pitch\GetPitchesRequest;
use Laraspace\Http\Requests\Pitch\GetPitchSizeWiseSummaryRequest;
// Need to Define Only Contracts
use Laraspace\Api\Contracts\PitchContract;

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
    public function getPitches(GetPitchesRequest $request, $tournamentId)
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
    public function getPitchSizeWiseSummary(GetPitchSizeWiseSummaryRequest $request, $tournamentId)
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
    public function createPitch(StoreRequest $request)
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
    public function edit(UpdateRequest $request,$pitchId)
    {
        return $this->pitchObj->edit($request,$pitchId);
    }
    public function show(ShowRequest $request, $pitchId)
    {
        return $this->pitchObj->getPitchData($pitchId);
    }
    public function deletePitch(DeleteRequest $request, $deleteId)
    {
        return $this->pitchObj->deletePitch($deleteId);
    }
    public function generatePitchMatchReport($pitchId)
    {
        return $this->pitchObj->generatePitchMatchReport($pitchId);
    }

    public function getSignedUrlForPitchMatchReport($pitchId)
    {
        $signedUrl = UrlSigner::sign(url('api/pitch/reportCard/' . $pitchId), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

}
