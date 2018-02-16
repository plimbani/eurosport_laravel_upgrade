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
    $historyData = $data['history'];
    $website_id = $data['websiteId'];
    $existingYearsIDs = $this->getAllYearIDs($website_id);

    $yearIndex = 0;
    $currentHistoryYearIDs = [];
    foreach ($historyData as $key => $historyYear) {
      unset($yearRow);
      $yearRow['id'] = $historyYear['id'];
      $yearRow['year'] = $historyYear['year'];
      $yearRow['website_id'] = $website_id;
      $yearRow['order'] = $yearIndex;

      $history_year_id = $this->saveHistoryYearData($yearRow);
      $currentYearIDs[] = $history_year_id;
      
      if(!empty($historyYear['age_categories'])) {
        $this->traverseAgeCategory($historyYear['age_categories'], $history_year_id, $website_id);
      }

      $yearIndex ++;
    }

    $deleteYearsIDs = array_diff($existingYearsIDs, $currentYearIDs);

    if(!empty($deleteYearsIDs)) {
      $this->deleteHistoryYears($deleteYearsIDs);
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
  * function to traverse into age category array
  * to save/update the data
  */
  public function traverseAgeCategory($ageCategories, $history_year_id, $website_id) {

    $existingCategoryIDs = $this->getAllCategoryIDs($website_id);

    $categoryIndex = 0;
    $currentCategoryIDs = [];
    foreach ($ageCategories as $key => $category) {
      unset($categoryRow);
      $categoryRow['id'] = $category['id'];
      $categoryRow['name'] = $category['name'];
      $categoryRow['website_id'] = $website_id;
      $categoryRow['order'] = $categoryIndex;
      $categoryRow['history_year_id'] = $history_year_id;

      $categoryID = $this->saveHistoryCategoryData($categoryRow);
      $currentCategoryIDs[] = $categoryID;

      if(!empty($category['teams'])) {
        $this->traverseCategoryTeams($category['teams'], $categoryID, $website_id);
      }

      $categoryIndex ++;
    }

    $deleteCategoryIDs = array_diff($existingCategoryIDs, $currentCategoryIDs);

    if(!empty($deleteCategoryIDs)) 
      $this->deleteAgeCategories($deleteCategoryIDs);

  }


  /*
  * function to traverse into teams array
  * to save/update the data
  */
  public function traverseCategoryTeams($teams, $category_id, $website_id) {

    $existingTeamIDs = $this->getAllTeamIDs($website_id);

    $teamsIndex = 0;
    $currentTeamIDs = [];
    foreach ($teams as $key => $team) {
      unset($teamRow);
      $teamRow['id'] = $team['id'];
      $teamRow['name'] = $team['name'];
      $teamRow['website_id'] = $website_id;
      $teamRow['history_age_category_id'] = $category_id;
      $teamRow['country_id'] = $team['country']['id'];
      $teamRow['order'] = $teamsIndex;

      $teamID = $this->saveHistoryTeamData($teamRow);
      $currentTeamIDs[] = $teamID;

      $teamsIndex ++;
    }

    $deleteTeamIDs = array_diff($existingTeamIDs, $currentTeamIDs);

    if(!empty($deleteTeamIDs)) 
      $this->deleteTeams($deleteTeamIDs);

  }
  /*
   * Get all year ids
   *
   * @return response
   */
  public function getAllYearIDs($websiteId)
  {
    $yearsIDs = HistoryYears::where('website_id', $websiteId)->pluck('id')->toArray();
    return $yearsIDs;
  }

  /*
   * Get all age category ids
   *
   * @return response
   */
  public function getAllCategoryIDs($websiteId)
  {
    $categoryIDs = HistoryAgeCategories::where('website_id', $websiteId)->pluck('id')->toArray();
    return $categoryIDs;
  }

  /*
   * Get all age category ids
   *
   * @return response
   */
  public function getAllTeamIDs($websiteId)
  {
    $teamIDs = HistoryTeams::where('website_id', $websiteId)->pluck('id')->toArray();
    return $teamIDs;
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
  * Delete history year
  * return response
  */

  public function deleteHistoryYears($yearIDs) {
    $response = HistoryYears::whereIn('id', $yearIDs)->delete();
    return $response;    
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
  * Delete age category
  * return response
  */
  public function deleteAgeCategories($categoryIDs) {
    $response = HistoryAgeCategories::whereIn('id', $categoryIDs)->delete();
    return $response;    
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

  /*
  * Delete team
  * return response
  */
  public function deleteTeams($teamIDs) {
    $response = HistoryTeams::whereIn('id', $teamIDs)->delete();
    return $response;    
  }

}