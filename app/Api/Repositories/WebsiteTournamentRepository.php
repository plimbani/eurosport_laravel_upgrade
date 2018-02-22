<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Page;
use Laraspace\Models\Website;
use Laraspace\Models\Country;
use Laraspace\Models\HistoryYear;
use Laraspace\Models\HistoryAgeCategory;
use Laraspace\Models\HistoryTeam;
use Laraspace\Api\Services\PageService;

class WebsiteTournamentRepository
{
  /**
   * @var Page service
   */
  protected $pageService;

  /*
  * @var Page name
  */
  protected $pageName;

  /*
  * @var Rule page name
  */
  protected $rulePageName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
    $this->pageName = 'tournament';
    $this->rulePageName = 'rules';
  }

  /*
   * Save website tournament data
   *
   * @return response
   */	
	public function savePageData($data)
	{
		// update website tournament page age categories
    $ageCategoryPageDetail = array();
    $ageCategoryPageDetail['name'] = $this->pageName;
    $ageCategoryPageDetail['content'] = $data['age_categories'];
    $this->pageService->updatePageDetails($ageCategoryPageDetail, $data['websiteId']);

    // update website tournament page rules
    $mealsPageDetail = array();
    $mealsPageDetail['name'] = $this->rulePageName;
    $mealsPageDetail['content'] = $data['rules'];
    $this->pageService->updatePageDetails($mealsPageDetail, $data['websiteId']);

    // Save history years
    $this->saveHistoryYears($data);
	}

  /*
   * Get all history years
   *
   * @return response
   */
  public function getAllHistoryYears($websiteId)
  {
    $historyYears = HistoryYear::with(['age_categories' => function($query){
      $query->with(['teams' => function($query) {
        $query->orderBy('order');
      }, 'teams.country'])->orderBy('order');
    }])->where('website_id', $websiteId)->orderBy('order')->get();

    return $historyYears;
  }

  /*
   * Get all year ids
   *
   * @return response
   */
  public function getAllYearIDs($websiteId)
  {
    $yearsIDs = HistoryYear::where('website_id', $websiteId)->pluck('id')->toArray();
    return $yearsIDs;
  }

  /*
   * Insert history year
   *
   * @return response
   */
  public function insertHistoryYear($websiteId, $data)
  {
    $historyYear = new HistoryYear();
    $historyYear->website_id = $websiteId;
    $historyYear->year = $data['year'];
    $historyYear->order = $data['order'];
    $historyYear->save();

    return $historyYear;
  }

  /*
   * Update history year
   *
   * @return response
   */
  public function updateHistoryYear($data)
  {
    $historyYear = HistoryYear::find($data['id']);
    $historyYear->year = $data['year'];
    $historyYear->order = $data['order'];
    $historyYear->save();

    return $historyYear;
  }

  /*
   * Delete history year
   *
   * @return response
   */
  public function deleteHistoryYear($historyYearId)
  {
    $historyYear = HistoryYear::find($historyYearId);
    if($historyYear->delete()) {
      return true;
    }
    return false;
  }

  /*
  * Delete multiple history years
  *
  * return response
  */
  public function deleteHistoryYears($yearIDs) {
    HistoryYear::whereIn('id', $yearIDs)->delete();

    return true;
  }

  /*
   * Delete multiple age categories by website id
   *
   * @return response
   */
  public function deleteHistoryYearsByWebsiteId($websiteId)
  {
    HistoryYear::where('website_id', $websiteId)->delete();

    return true;
  }

  /*
   * Get all age category ids
   *
   * @return response
   */
  public function getAllAgeCategoryIds($yearId, $websiteId)
  {
    $ageCategoryIds = HistoryAgeCategory::where('website_id', $websiteId)->where('history_year_id', $yearId)->pluck('id')->toArray();

    return $ageCategoryIds;
  }

  /*
   * Insert age category
   *
   * @return response
   */
  public function insertAgeCategory($yearId, $websiteId, $data)
  {
    $ageCategory = new HistoryAgeCategory();
    $ageCategory->website_id = $websiteId;
    $ageCategory->history_year_id = $yearId;
    $ageCategory->name = $data['name'];
    $ageCategory->order = $data['order'];
    $ageCategory->save();

    return $ageCategory;
  }

  /*
   * Update age category
   *
   * @return response
   */
  public function updateAgeCategory($data)
  {
    $ageCategory = HistoryAgeCategory::find($data['id']);
    $ageCategory->name = $data['name'];
    $ageCategory->order = $data['order'];
    $ageCategory->save();

    return $ageCategory;
  }

  /*
   * Delete age category
   *
   * @return response
   */
  public function deleteAgeCategory($ageCategoryId)
  {
    $ageCategory = HistoryAgeCategory::find($ageCategoryId);
    if($ageCategory->delete()) {
      return true;
    }
    return false;
  }

  /*
  * Delete age category
  * return response
  */
  public function deleteAgeCategories($categoryIDs) {
    HistoryAgeCategory::whereIn('id', $categoryIDs)->delete();
    return true;
  }

  /*
   * Delete multiple age categories by website id
   *
   * @return response
   */
  public function deleteAgeCategoriesByWebsiteId($websiteId)
  {
    HistoryAgeCategory::where('website_id', $websiteId)->delete();
    return true;
  }

  /*
   * Get all teams ids
   *
   * @return response
   */
  public function getAllTeamIDs($ageCategoryId, $websiteId)
  {
    $teamIDs = HistoryTeam::where('website_id', $websiteId)->where('history_age_category_id', $ageCategoryId)->pluck('id')->toArray();
    return $teamIDs;
  }

  /*
   * Insert age category team
   *
   * @return response
   */
  public function insertTeam($ageCategoryId, $websiteId, $data)
  {
    $team = new HistoryTeam();
    $team->website_id = $websiteId;
    $team->history_age_category_id = $ageCategoryId;
    $team->name = $data['name'];
    $team->order = $data['order'];
    $team->country_id = $data['country']['id'];
    $team->save();

    return $team;
  }

  /*
   * Update age category team
   *
   * @return response
   */
  public function updateTeam($data)
  {
    $team = HistoryTeam::find($data['id']);
    $team->name = $data['name'];
    $team->order = $data['order'];
    $team->country_id = $data['country']['id'];
    $team->save();

    return $team;
  }

  /*
   * Delete age category team
   *
   * @return response
   */
  public function deleteTeam($teamId)
  {
    $team = HistoryTeam::find($teamId);
    if($team->delete()) {
      return true;
    }
    return false;
  }

  /*
  * Delete team
  * return response
  */
  public function deleteTeams($teamIDs) {
    HistoryTeam::whereIn('id', $teamIDs)->delete();
    return true;
  }

  /*
   * Delete multiple age category teams by website id
   *
   * @return response
   */
  public function deleteTeamsByWebsiteId($websiteId)
  {
    HistoryTeam::where('website_id', $websiteId)->delete();
    return true;
  }

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    $pages = [$this->pageName, $this->rulePageName];
    $response = $this->pageService->getMultiplePagesData($pages, $websiteId);

    $history = $this->getAllHistoryYears($websiteId);
    $response['history'] = $history;

    $countries = Country::all();
    $response['countries'] = $countries;

    return $response;
  }

  /*
   * Save age categories
   *
   * @return response
   */
  public function saveHistoryYears($data)
  {
    $websiteId = $data['websiteId'];
    $historyYears = $data['history'];

    $existingYearIds = $this->getAllYearIDs($websiteId);

    $yearIds = [];
    for($i=0; $i<count($historyYears); $i++) {
      $historyYearData = $historyYears[$i];
      $historyYearData['order'] = $i + 1;
      if($historyYearData['id'] == '') {
        $historyYear = $this->insertHistoryYear($websiteId, $historyYearData);
      } else {
        $historyYear = $this->updateHistoryYear($historyYearData);
      }
      $this->saveAgeCategories($historyYearData['age_categories'], $historyYear->id, $websiteId);
      $yearIds[] = $historyYear->id;
    }

    $deleteHistoryYearsIds = array_diff($existingYearIds, $yearIds);

    $this->deleteHistoryYears($deleteHistoryYearsIds);
  }

  /*
   * Save age categories
   *
   * @return response
   */
  public function saveAgeCategories($ageCategories, $yearId, $websiteId)
  {
    $existingAgeCategoriesId = $this->getAllAgeCategoryIds($yearId, $websiteId);

    $ageCategoriesIds = [];
    for($i=0; $i<count($ageCategories); $i++) {
      $ageCategoryData = $ageCategories[$i];
      $ageCategoryData['order'] = $i + 1;
      if($ageCategoryData['id'] == '') {
        $ageCategory = $this->insertAgeCategory($yearId, $websiteId, $ageCategoryData);
      } else {
        $ageCategory = $this->updateAgeCategory($ageCategoryData);
      }
      $this->saveAgeCategoryTeams($ageCategoryData['teams'], $ageCategory->id, $websiteId);
      $ageCategoriesIds[] = $ageCategory->id;
    }

    $deleteAgeCategoriesIds = array_diff($existingAgeCategoriesId, $ageCategoriesIds);

    $this->deleteAgeCategories($deleteAgeCategoriesIds);
  }

  /*
   * Save age category teams
   *
   * @return response
   */
  public function saveAgeCategoryTeams($teams, $ageCategoryId, $websiteId)
  {
    $existingTeamsIds = $this->getAllTeamIds($ageCategoryId, $websiteId);

    $teamIds = [];
    for($i=0; $i<count($teams); $i++) {
      $teamData = $teams[$i];
      $teamData['order'] = $i + 1;

      if($teamData['id'] == '') {
        $team = $this->insertTeam($ageCategoryId, $websiteId, $teamData);
      } else {
        $team = $this->updateTeam($teamData);
      }
      $teamIds[] = $team->id;
    }

    $deleteAgeCategoryTeamsId = array_diff($existingTeamsIds, $teamIds);

    $this->deleteTeams($deleteAgeCategoryTeamsId);
  }
}