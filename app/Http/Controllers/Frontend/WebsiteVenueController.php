<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\VenueContract;

class WebsiteVenueController extends Controller
{
    /**
     * @var VenueContract
     */
    protected $venueContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VenueContract $venueContract)
    {
        $this->venueContract = $venueContract;
    }

    /**
     * Get venue page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVenuePageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.venue', $varsForView);
    }
}
