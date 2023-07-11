<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Laraspace\Api\Contracts\WOTContract;

class WOTController extends BaseController
{
    /**
   * @var wotContract
   */
    protected $wotContract;

    public function __construct(WOTContract $wotContract)
    {
        $this->wotContract = $wotContract;
    }

    public function getWebsiteId(Request $request)
    {
        return $this->wotContract->getWebsiteId($request);
    }

    public function getLocations(Request $request)
    {
        return $this->wotContract->getLocations($request);
    }

    public function getMarkers(Request $request)
    {
        return $this->wotContract->getMarkers($request);
    }

    public function getContact(Request $request)
    {
        return $this->wotContract->getContact($request);
    }

    public function getAccommodation(Request $request)
    {
        return $this->wotContract->getAccommodation($request);
    }

    public function getVisitors(Request $request)
    {
        return $this->wotContract->getVisitors($request);
    }

    public function getTouristInformation(Request $request)
    {
        return $this->wotContract->getTouristInformation($request);
    }

    public function getTips(Request $request)
    {
        return $this->wotContract->getTips($request);
    }

    public function getProgram(Request $request)
    {
        return $this->wotContract->getProgram($request);
    }

    public function getSponsors(Request $request)
    {
        return $this->wotContract->getSponsors($request);
    }

    public function getOrganiser(Request $request)
    {
        return $this->wotContract->getOrganiser($request);
    }
}