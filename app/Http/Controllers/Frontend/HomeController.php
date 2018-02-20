<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\HomeContract;

class HomeController extends Controller
{
    /**
     * @var HomeContract
     */
    protected $homeContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeContract $homeContract)
    {
        $this->homeContract = $homeContract;
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomePageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $varsForView['statistics'] = $this->homeContract->getStatistics($websiteId)['data'];
        $varsForView['pageDetails'] = $this->homeContract->getPageData($websiteId)['data'];

        return view('frontend.home', $varsForView);
    }
}
