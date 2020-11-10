<?php

namespace Laraspace\Http\Controllers\Frontend;

use App;
use Landlord;
use Laraspace\Models\Page;
use Illuminate\Http\Request;
use Laraspace\Models\Tournament;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\WebsiteTournamentContract;
use Laraspace\Api\Services\TournamentAPI\Client\HttpClient;

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
     * @var Tournament age category page name
     */
    protected $tournamentAgeCategoryPageName;

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
    public function __construct(WebsiteTournamentContract $websiteTournamentContract, PageService $pageService)
    {
      $this->pageService = $pageService;
      $this->websiteTournamentContract = $websiteTournamentContract;
      $this->tournamentPageName = 'tournament';
      $this->tournamentAgeCategoryPageName = 'age_categories';
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
      $pageDetail = $this->pageService->getPageDetails($this->tournamentAgeCategoryPageName,
          $websiteId);
      $varsForView['tournamentContent'] = $pageDetail;

      $pageParentId = $pageDetail->parent_id;
      $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);
      $varsForView['additionalPages'] = $additionalPages;

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

      $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);
      $varsForView['additionalPages'] = $additionalPages;

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
      $tournament = $website->linked_tournament!=null ? Tournament::find($website->linked_tournament)->toArray() : null;

      // Get competition list for final placing.
      $data = [];
      $competitionListData = [];

      if($tournament) {
        $data['tournamentData'] = ['tournament_id' => $tournament['id']];
        $client = new HttpClient();
        $competitionList = $client->post('/age_group/getCompetationFormat', [], $data);
        $competitionListData = json_decode($competitionList)->data;
      }

      $allHistoryYears = $this->websiteTournamentContract->getAllHistoryYears($websiteId);

      $additionalPages = $this->pageService->getAdditionalPagesByParentId($pageParentId, $websiteId);
      $varsForView['additionalPages'] = $additionalPages;

      // page title
      $varsForView['pageTitle'] = $parentPage->title . ' - ' . $pageDetail->title;
      $varsForView['tournament'] = $tournament;
      $varsForView['competitionList'] = $competitionListData;
      $varsForView['allHistoryYears'] = $allHistoryYears['data'];

      return view('frontend.history', $varsForView);
    }

    /**
     * Get additional tournament page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdditionalTournamentPageDetails(Request $request, $domain, $additionalPageName)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $parentPageDetail = $this->pageService->getPageDetails($this->tournamentPageName, $websiteId);
      $page = Page::where('parent_id', $parentPageDetail->id)
                    ->where('website_id', $websiteId)
                    ->where('is_published', 1)
                    ->where('page_name', $additionalPageName)
                    ->first();

      if(!$page) {
        App::abort(404);
      }

      $varsForView['additionalPage'] = $page;

      $additionalPages = $this->pageService->getAdditionalPagesByParentId($parentPageDetail->id, $websiteId);
      $varsForView['additionalPages'] = $additionalPages;

      // page title
      $varsForView['pageTitle'] = $parentPageDetail->title . ' - ' . $page->title;

      return view('frontend.tournament_additional_page', $varsForView);
    }
}
