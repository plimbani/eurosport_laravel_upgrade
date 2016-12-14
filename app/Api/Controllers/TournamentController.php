<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use App\Api\Contracts\TournamentContract;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 * @Author Knayak@aecordigital.com
 */
class TournamentController extends BaseController
{
    public function __construct(TournamentContract $tournamentObj)
    {
        $this->tournamentObj = $tournamentObj;
    }

    /**
     * Show all Tournament Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/tournament")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getAllTournaments()
    {
        /* return response(array(
        'error' => false,
        'products' =>$products->toArray(),
       ),200); */
        return $this->tournamentObj->getAllTournaments();
    }

    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/tournament/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createTournament(Request $request)
    {
        return $this->tournamentObj->createTournament($request);
    }
}
