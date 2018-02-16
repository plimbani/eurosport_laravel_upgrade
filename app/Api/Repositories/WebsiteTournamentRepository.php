<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Models\HistoryYears;
use Laraspace\Models\HistoryAgeCategories;
use Laraspace\Models\HistoryTeams;
use Laraspace\Api\Services\PageService;

class WebsiteTournamentRepository
{
  /**
   * @var Page service
   */
  protected $pageService;
  /*
  * @var Tournament page title
  */
  protected $age_categories;
  /*
  * @var Rules page title
  */
  protected $rules;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
    $this->age_categories = 'Tournament';
    $this->rules = 'Rules';
  }

  /*
   * Save website tournament data
   *
   * @return response
   */	
	public function saveWebsiteTournamentPageData($data)
	{
    // echo "<pre>"; print_r($data['history']); echo "</pre>"; exit;
    $historyData = $data['history'];

    $website_id = $data['websiteId'];

    $yearIndex = $categoryIndex = $teamsIndex = 0;

    foreach ($historyData as $key => $historyYear) {
      unset($yearRow);
      $yearRow['id'] = $historyYear['id'];
      $yearRow['year'] = $historyYear['year'];
      $yearRow['website_id'] = $website_id;
      $yearRow['order'] = $yearIndex;

      $history_year_id = $this->saveHistoryYearData($yearRow);

      $categoryIndex = 0;
      foreach ($historyYear['categoryList'] as $key => $category) {
        unset($categoryRow);
        $categoryRow['id'] = $category['id'];
        $categoryRow['name'] = $category['name'];
        $categoryRow['website_id'] = $website_id;
        $categoryRow['order'] = $categoryIndex;
        $categoryRow['history_year_id'] = $history_year_id;

        $categoryID = $this->saveHistoryCategoryData($categoryRow);

        $teamsIndex = 0;
        foreach ($category['teams'] as $key => $team) {
          unset($teamRow);
          $teamRow['id'] = $team['id'];
          $teamRow['name'] = $team['name'];
          $teamRow['website_id'] = $website_id;
          $teamRow['history_age_category_id'] = $categoryID;
          $teamRow['country_id'] = $team['country']['id'];
          $teamRow['order'] = $teamsIndex;
          $teamRow['history_year_id'] = $history_year_id;

          $teamID = $this->saveHistoryTeamData($teamRow);

          $teamsIndex ++;
        }

        $categoryIndex ++;
      }

      $yearIndex ++;
    }

		// update website tournament page age categories
    $wtPageDetail = array();
    $wtPageDetail['name'] = $this->age_categories;
    $wtPageDetail['content'] = $data['age_categories'];
    $this->pageService->updatePageDetails($wtPageDetail, $data['websiteId']);

    // update website tournament page rules
    $mealsPageDetail = array();
    $mealsPageDetail['name'] = $this->rules;
    $mealsPageDetail['content'] = $data['rules'];
    $this->pageService->updatePageDetails($mealsPageDetail, $data['websiteId']);
	}

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getWebsiteTournamentPageData($websiteId)
  {
    $pages = [$this->age_categories, $this->rules];
    $response = $this->pageService->getMultiplePagesData($pages, $websiteId);

    $history = HistoryYears::with(['age_categories' => function($query){
      $query->with(['teams' => function($query) {
        $query->orderBy('order');
      }, 'teams.country'])->orderBy('order');
    }])->where('website_id', $websiteId)->get();

    $response['history'] = $history;
    return $response;
  }

  /*
   * Save history year data
   *
   * @return last inserted year id
   */
  public function saveHistoryYearData($data) {

    if(isset($data['id']) && $data['id'] != null){
      $id = $data['id'];
      $historyYear = HistoryYears::find($id);
    } else {
      $historyYear = new HistoryYears();
    }

    $historyYear->year = $data['year'];
    $historyYear->website_id = $data['website_id'];
    $historyYear->order = $data['order'];
    $historyYear->save();
    return $historyYear->id;
  }

  /*
   * Save history category data
   *
   * @return last inserted category id
   */
  public function saveHistoryCategoryData($data) {
    
    if(isset($data['id']) && $data['id'] != null){
      $id = $data['id'];
      $historyCategory = HistoryAgeCategories::find($id);
    } else {
      $historyCategory = new HistoryAgeCategories();
    }

    $historyCategory->name = $data['name'];
    $historyCategory->website_id = $data['website_id'];
    $historyCategory->order = $data['order'];
    $historyCategory->history_year_id = $data['history_year_id'];
    $historyCategory->save();
    return $historyCategory->id;

  }

  /*
   * Save history category team data
   *
   * @return last inserted team id
   */
  public function saveHistoryTeamData($data) {
    
    if(isset($data['id']) && $data['id'] != null){
      $id = $data['id'];
      $historyTeam = HistoryTeams::find($id);
    } else {
      $historyTeam = new HistoryTeams();
    }

    $historyTeam->name = $data['name'];
    $historyTeam->website_id = $data['website_id'];
    $historyTeam->history_age_category_id = $data['history_age_category_id'];
    $historyTeam->country_id = $data['country_id'];
    $historyTeam->order = $data['order'];
    $historyTeam->save();
    return $historyTeam->id;

  }

}