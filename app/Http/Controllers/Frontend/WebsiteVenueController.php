<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\WebsiteVenueContract;

class WebsiteVenueController extends Controller
{
    /**
     * @var WebsiteVenueContract
     */
    protected $websiteVenueContract;

    /**
     * @var Venue page name
     */
    protected $venuePageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteVenueContract $websiteVenueContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->websiteVenueContract = $websiteVenueContract;
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
        $varsForView['locations'] = $this->websiteVenueContract->getLocations($websiteId)['data'];
        $varsForView['markers'] = $this->websiteVenueContract->getMarkers($websiteId)['data'];
        
        return view('frontend.venue', $varsForView);
    }
}
