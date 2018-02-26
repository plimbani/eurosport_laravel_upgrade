<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\VisitorContract;
use Laraspace\Api\Services\PageService;

class VisitorController extends Controller
{
    /**
     * @var VisitorContract
     */
    protected $visitorContract;

    /**
     * @var Visitors page name
     */
    protected $visitorsPageName;

     /**
     * @var Tourist page name
     */
    protected $touristPageName;

    /**
     * @var Page service
     */
    protected $pageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VisitorContract $visitorContract, PageService $pageService)
    {
        $this->visitorContract = $visitorContract;
        $this->pageService = $pageService;
        $this->visitorPageName = 'visitors';
        $this->touristPageName = 'tourist_information';
    }

    /**
     * Get visitor page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVisitorPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $visitorsContent = $this->pageService->getPageDetails($this->visitorPageName, $websiteId);
        
        $varsForView['arrivalCheckInInformation'] = isset($visitorsContent->meta['arrival_check_in_information']) ? $visitorsContent->meta['arrival_check_in_information'] : '';
       
        $varsForView['publicTransport'] = isset($visitorsContent->meta['public_transport']) ? $visitorsContent->meta['public_transport'] : '';
       
        $varsForView['tips'] = isset($visitorsContent->meta['tips']) ? $visitorsContent->meta['tips'] : '';
        
        return view('frontend.visitor', $varsForView);
    }

    /**
     * Get tourist page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTouristPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $varsForView['touristContent'] = $this->pageService->getPageDetails($this->touristPageName, 
            $websiteId);
        return view('frontend.tourist', $varsForView);
    }
}
