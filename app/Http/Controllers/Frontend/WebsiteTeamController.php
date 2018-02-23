<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\WebsiteTeamContract;

class WebsiteTeamController extends Controller
{
    /**
     * @var WebsiteTeamContract
     */
    protected $websiteTeamContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteTeamContract $websiteTeamContract)
    {
        $this->websiteTeamContract = $websiteTeamContract;
    }

    /**
     * Get team page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeamPageDetails(Request $request)
    {
        $websiteId = Landlord::getTenants()['website']->id;

        $ageCategories = $this->websiteTeamContract->getAgeCategories($websiteId)['data'];

        return view('frontend.team', compact('ageCategories'));
    }
}
