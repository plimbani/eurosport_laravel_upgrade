<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\MatchContract;

class MatchController extends Controller
{
    /**
     * @var MatchContract
     */
    protected $matchContract;

    /**
     * @var Match page name
     */
    protected $matchPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MatchContract $matchContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->matchContract = $matchContract;
        $this->matchPageName = 'matches';
    }

    /**
     * Get matches page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMatchPageDetails(Request $request)
    {
        $varsForView = [];
        $website = Landlord::getTenants()['website'];
        $websiteId = $website->id;
        $pageDetail = $this->pageService->getPageDetails($this->matchPageName, $websiteId);
        $tournament = $website->linked_tournament!=null ? Tournament::find($website->linked_tournament)->toArray() : null;

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;
        $varsForView['tournament'] = $tournament;

        return view('frontend.matches', $varsForView);
    }
}
