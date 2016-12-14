<?php

namespace App\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use App\Api\Contracts\MatchContract;

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

    public function deleteMatch($deleteId)
    {
        return $this->matchObj->deleteMatch($deleteId);
    }
}
