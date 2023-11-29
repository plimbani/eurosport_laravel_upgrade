<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Api\Contracts\VenueContract;
// Need to Define Only Contracts
use App\Http\Requests\Venue\GetVenueRequest;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 * @Author Knayak@aecordigital.com
 */
class VenueController extends BaseController
{
    /**
     * @param  object  $tournamentObj
     */
    public function __construct(VenueContract $venueObj)
    {
        $this->venueObj = $venueObj;
    }

    /**
     * Show all Tournament Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/tournament")
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function index()
    {
        return $this->venueObj->index();
    }

    /**
     * Show json Data for Template.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/templates")
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={"id": 10, "json": "foo"})
     */
    public function getVenues(GetVenueRequest $request, $tournamentId)
    {
        return $this->venueObj->index($tournamentId);
    }

    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/tournament/create")
     *
     * @Versions({"v1"})
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function create(Request $request)
    {
        return $this->venueObj->create($request);
    }

    /**
     * Edit  Torunament.
     *
     * @Post("/tournament/edit/{$id}")
     *
     * @Versions({"v1"})
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->venueObj->edit($request);
    }

    /**
     * Delete  Torunament.
     *
     * @Post("/tournament/delete")
     *
     * @Versions({"v1"})
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(Request $request)
    {
        return $this->venueObj->delete($request);
    }
}
