<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;

class MatchController extends Controller
{
    /**
     * @var Match page name
     */
    protected $matchPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
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
        $client = new HttpClient();
        $login = $client->login();
        $tournament = $website->linked_tournament!=null ? json_decode($client->post('/tournaments/tournamentSummary', ['Authorization' => 'Bearer '.json_decode($login)->token], ['tournamentId' => $website->linked_tournament]))->data->tournament_detail : null;

        $data = [];
        $competitionListData = [];

        if($tournament) {
            $data['tournamentData'] = ['tournament_id' => $tournament->id];
            $client = new HttpClient();
            $login = $client->login();
            $competitionList = $client->post('/age_group/getCompetationFormat', ['Authorization' => 'Bearer '.json_decode($login)->token], $data);
            $competitionListData = json_decode($competitionList)->data;
        }
        $varsForView['competitionList'] = $competitionListData;

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;
        $varsForView['tournament'] = $tournament;

        return view('frontend.matches', $varsForView);
    }
}
