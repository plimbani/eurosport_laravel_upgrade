<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Laraspace\Models\Page;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\AgeGroupContract;
use Laraspace\Api\Contracts\WebsiteTournamentContract;

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
     * @var History page name
     */
    protected $historyPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteTournamentContract $websiteTournamentContract, PageService $pageService, AgeGroupContract $ageGroupObj)
    {
      $this->ageGroupObj = $ageGroupObj;
      $this->pageService = $pageService;
      $this->websiteTournamentContract = $websiteTournamentContract;
      $this->tournamentPageName = 'tournament';
      $this->rulesPageName = 'rules';
      $this->historyPageName = 'history';
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
      $pageDetail = $this->pageService->getPageDetails($this->tournamentPageName,
          $websiteId);
      $varsForView['tournamentContent'] = $pageDetail;

      // Page title
      $varsForView['pageTitle'] = $pageDetail->title;

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
      $pageDetail = $this->pageService->getPageDetails($this->rulesPageName, $websiteId);
      $pageParentId = $pageDetail->parent_id;
      $parentPage = Page::find($pageParentId);

      $varsForView['rulesContent'] = $pageDetail;

      // page title
      $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;

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
      $website = Landlord::getTenants()['website'];
      $websiteId = $website->id;
      $pageDetail = $this->pageService->getPageDetails($this->historyPageName, $websiteId);
      $pageParentId = $pageDetail->parent_id;
      $parentPage = Page::find($pageParentId);
      $tournament = $website->linked_tournament!=null ? Tournament::find($website->linked_tournament) : null;

      // Get competition list for final placing.
      $data = [];
      $data['tournamentData'] = ['tournament_id' => $tournament->id];
      $competitionList = $this->ageGroupObj->GetCompetationFormat($data);

      $allHistoryYears = $this->websiteTournamentContract->getAllHistoryYears($websiteId);

      // page title
      $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;
      $varsForView['tournament'] = $tournament->toArray();
      $varsForView['competitionList'] = $competitionList['data'];
      $varsForView['allHistoryYears'] = $allHistoryYears['data'];

      return view('frontend.history', $varsForView);
    }
}
