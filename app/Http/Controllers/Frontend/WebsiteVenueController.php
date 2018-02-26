<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\VenueContract;

class WebsiteVenueController extends Controller
{
    /**
     * @var VenueContract
     */
    protected $venueContract;

    /**
     * @var Home page name
     */
    protected $venuePageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VenueContract $venueContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->venueContract = $venueContract;
        $this->venuePageName = 'venue';
    }

    /**
     * Get venue page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVenuePageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $pageDetail = $this->pageService->getPageDetails($this->venuePageName, $websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;

        return view('frontend.venue', $varsForView);
    }
}
