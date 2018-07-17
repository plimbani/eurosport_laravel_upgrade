<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Laraspace\Models\Page;
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
     * @var Visitors page name
     */
    protected $tipsPageName;

    /**
     * @var Visitors page name
     */
    protected $publicTransportPageName;

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
        $this->tipsPageName = 'tips';
        $this->publicTransportPageName = 'public_transport';
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
        $pageParentId = $visitorsContent->parent_id;

        // Page title
        $varsForView['pageTitle'] = $visitorsContent->title;

        $varsForView['visitorsContent'] = $visitorsContent;

        $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);

        $varsForView['additionalPages'] = $additionalPages;

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
        $pageDetail = $this->pageService->getPageDetails($this->touristPageName, $websiteId);
        $pageParentId = $pageDetail->parent_id;
        $parentPage = Page::find($pageParentId);

        $varsForView['touristContent'] = $pageDetail;

        // page title
        $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;

        $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);

        $varsForView['additionalPages'] = $additionalPages;

        return view('frontend.tourist', $varsForView);
    }

    /**
     * Get tips page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTipsPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $tipsContent = $this->pageService->getPageDetails($this->tipsPageName, $websiteId);
        $pageParentId = $tipsContent->parent_id;

        // Page title
        $varsForView['pageTitle'] = $tipsContent->title;

        $varsForView['tipsContent'] = $tipsContent;

        $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);

        $varsForView['additionalPages'] = $additionalPages;

        return view('frontend.tips', $varsForView);
    }

    /**
     * Get tips page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPublicTransportPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $publicTransportContent = $this->pageService->getPageDetails($this->publicTransportPageName, $websiteId);
        $pageParentId = $publicTransportContent->parent_id;

        // Page title
        $varsForView['pageTitle'] = $publicTransportContent->title;

        $varsForView['publicTransport'] = $publicTransportContent;

        $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);

        $varsForView['additionalPages'] = $additionalPages;

        return view('frontend.public_transport', $varsForView);
    }
}
