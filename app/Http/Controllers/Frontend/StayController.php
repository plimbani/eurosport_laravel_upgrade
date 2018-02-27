<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Laraspace\Models\Page;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\StayContract;

class StayController extends Controller
{
    /**
     * @var StayContract
     */
    protected $stayContract;

    /**
     * @var Stay page name
     */
    protected $stayPageName;

    /**
     * @var Meals page name
     */
    protected $mealsPageName;

    /**
     * @var Accommodation page name
     */
    protected $accommodationPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StayContract $stayContract, PageService $pageService)
    {
      $this->pageService = $pageService;
      $this->stayContract = $stayContract;
      $this->stayPageName = 'stay';
      $this->mealsPageName = 'meals';
      $this->accommodationPageName = 'accommodation';
    }

    /**
     * Get stay page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStayPageDetails(Request $request)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $pageDetail = $this->pageService->getPageDetails($this->stayPageName, $websiteId);
      $varsForView['stayContent'] = $pageDetail;
     
      $pageParentId = $pageDetail->id;

      $additionalPageName = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);
      
      $varsForView['additionalPageContent'] = $additionalPageName;

      // Page title
      $varsForView['pageTitle'] = $pageDetail->title;

      return view('frontend.stay', $varsForView);
    }

    /**
     * Get meals page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMealsPageDetails(Request $request)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $pageDetail = $this->pageService->getPageDetails($this->mealsPageName, $websiteId);

      $pageParentId = $pageDetail->parent_id;
      
      $parentPage = Page::find($pageParentId);
      $varsForView['mealsContent'] = $pageDetail;

      // page title
      $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;

      return view('frontend.meals', $varsForView);
    }

    /**
     * Get accommodation page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccommodationPageDetails(Request $request)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $pageDetail = $this->pageService->getPageDetails($this->accommodationPageName, $websiteId);
      $pageParentId = $pageDetail->parent_id;
      $parentPage = Page::find($pageParentId);

      $varsForView['accommodationContent'] = $pageDetail;
      // page title
      $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;

      return view('frontend.accommodation', $varsForView);
    }

    /**
     * Get additional stay page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdditionalStayPageDetails(Request $request, $domain, $additionalPageName)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $parentPageDetail = $this->pageService->getPageDetails($this->stayPageName, $websiteId);
      $page = Page::where('parent_id', $parentPageDetail->id)
                    ->where('website_id', $websiteId)
                    ->where('page_name', $additionalPageName)
                    ->first();

      // page title
      $varsForView['pageTitle'] = $parentPageDetail->title . ' - ' . $page->title;

      return view('frontend.stay', $varsForView);
    }
}
