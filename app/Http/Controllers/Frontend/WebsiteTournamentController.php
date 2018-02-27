<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\WebsiteTournamentContract;
use Laraspace\Api\Services\PageService;

class WebsiteTournamentController extends Controller
{
    /**
     * @var WebsiteTournamentContract
     */
    protected $websiteTournamentContract;

    /**
     * @var Tournament page name
     */
    protected $tournamentPageName;

    /**
     * @var Page service
     */
    protected $pageService;

    /**
     * @var Rules page name
     */
    protected $rulesPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteTournamentContract $websiteTournamentContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->websiteTournamentContract = $websiteTournamentContract;
        $this->tournamentPageName = 'tournament';
        $this->rulesPageName = 'rules';
    }

    /**
     * Get tournament page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTournamentPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $varsForView['tournamentContent'] = $this->pageService->getPageDetails($this->tournamentPageName, 
            $websiteId);

        return view('frontend.tournament', $varsForView);
    }

    /**
     * Get rules page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRulesPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $varsForView['rulesContent'] = $this->pageService->getPageDetails($this->rulesPageName, 
            $websiteId);

        return view('frontend.rules', $varsForView);
    }

    /**
     * Get history page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistoryPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.history', $varsForView);
    }
}
