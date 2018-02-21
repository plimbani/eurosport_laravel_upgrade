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
        $varsForView = [];

        return view('frontend.team', $varsForView);
    }
}
