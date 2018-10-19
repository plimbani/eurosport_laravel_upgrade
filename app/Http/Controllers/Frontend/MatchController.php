<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\MatchContract;
use Laraspace\Api\Contracts\AgeGroupContract;

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
    public function __construct(MatchContract $matchContract, PageService $pageService, AgeGroupContract $ageGroupObj)
    {
        $this->pageService = $pageService;
        $this->matchContract = $matchContract;
        $this->matchPageName = 'matches';
        $this->ageGroupObj = $ageGroupObj;
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
        $tournament = $website->linked_tournament!=null ? Tournament::where('id', $website->linked_tournament)->where('status', 'Published')->first() : null;

        $data = [];
        $competitionListData = [];

        if($tournament) {
            $tournament = $tournament->toArray();
            $data['tournamentData'] = ['tournament_id' => $tournament['id']];
            $competitionList = $this->ageGroupObj->GetCompetationFormat($data);
            $competitionListData = $competitionList['data'];
        }
        $varsForView['competitionList'] = $competitionListData;

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;
        $varsForView['tournament'] = $tournament;

        return view('frontend.matches', $varsForView);
    }
}
