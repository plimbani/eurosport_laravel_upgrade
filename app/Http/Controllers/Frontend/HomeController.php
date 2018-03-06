<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\HomeContract;

class HomeController extends Controller
{
    /**
     * @var HomeContract
     */
    protected $homeContract;

    /**
     * @var Home page name
     */
    protected $homePageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeContract $homeContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->homeContract = $homeContract;
        $this->homePageName = 'home';
    }

    /**
     * Get home page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomePageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $pageDetail = $this->pageService->getPageDetails($this->homePageName, $websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;

        $varsForView['statistics'] = $this->homeContract->getStatistics($websiteId)['data'];
        $varsForView['pageDetails'] = $this->homeContract->getPageData($websiteId)['data'];

        return view('frontend.home', $varsForView);
    }
}
