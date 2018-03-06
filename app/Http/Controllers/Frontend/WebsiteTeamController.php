<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\WebsiteTeamContract;

class WebsiteTeamController extends Controller
{
    /**
     * @var WebsiteTeamContract
     */
    protected $websiteTeamContract;

    /**
     * @var Team page name
     */
    protected $websiteTeamPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteTeamContract $websiteTeamContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->websiteTeamContract = $websiteTeamContract;
        $this->websiteTeamPageName = 'teams';
    }

    /**
     * Get team page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeamPageDetails(Request $request)
    {
        $websiteId = Landlord::getTenants()['website']->id;
        $pageDetail = $this->pageService->getPageDetails($this->websiteTeamPageName, $websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;

        $varsForView['ageCategories'] = $this->websiteTeamContract->getAgeCategories($websiteId)['data'];

        return view('frontend.team', $varsForView);
    }
}
