<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Country;
use Laraspace\Models\AgeCategory;
use Laraspace\Models\AgeCategoryTeam;
use Laraspace\Api\Services\PageService;

class WebsiteTeamRepository
{

  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->pageService = $pageService;
  }

  /*
   * Get all age categories
   *
   * @return response
   */
  public function getAllAgeCategories($websiteId)
  {
    $ageCategories = AgeCategory::with(['teams' => function($query){
            $query->orderBy('order');
          }, 'teams.country'])->where('website_id', $websiteId)->orderBy('order')->get();
    return $ageCategories;
  }

  /*
   * Get all age category ids
   *
   * @return response
   */
  public function getAllAgeCategoryIds($websiteId)
  {
    $ageCategoryIds = AgeCategory::where('website_id', $websiteId)->pluck('id')->toArray();
    return $ageCategoryIds;
  }

  /*
   * Insert age category
   *
   * @return response
   */
  public function insertAgeCategory($websiteId, $data)
  {
    $ageCategory = new AgeCategory();
    $ageCategory->website_id = $websiteId;
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
    $ageCategory = AgeCategory::find($data['id']);
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
    $ageCategory = AgeCategory::find($ageCategoryId);
    if($ageCategory->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple age categories
   *
   * @return response
   */
  public function deleteAgeCategories($ageCategoryIds = [])
  {
    AgeCategory::whereIn('id', $ageCategoryIds)->delete();
    return true;
  }

  /*
   * Get all age category teams
   *
   * @return response
   */
  public function getAllAgeCategoryTeams($websiteId)
  {
    $ageCategoryTeams = AgeCategoryTeam::where('website_id', $websiteId)->orderBy('order')->get();

    return $ageCategoryTeams;
  }

  /*
   * Get all age category teams ids
   *
   * @return response
   */
  public function getAllAgeCategoryTeamIds($ageCategoryId, $websiteId)
  {
    $ageCategoryTeamIds = AgeCategoryTeam::where('website_id', $websiteId)->where('age_category_id', $ageCategoryId)->pluck('id')->toArray();

    return $ageCategoryTeamIds;
  }

  /*
   * Insert age category team
   *
   * @return response
   */
  public function insertAgeCategoryTeam($ageCategoryId, $websiteId, $data)
  {
    $ageCategoryTeam = new AgeCategoryTeam();
    $ageCategoryTeam->website_id = $websiteId;
    $ageCategoryTeam->age_category_id = $ageCategoryId;
    $ageCategoryTeam->name = $data['name'];
    $ageCategoryTeam->order = $data['order'];
    $ageCategoryTeam->country_id = $data['country']['id'];
    $ageCategoryTeam->save();

    return $ageCategoryTeam;
  }

  /*
   * Update age category team
   *
   * @return response
   */
  public function updateAgeCategoryTeam($data)
  {
    $ageCategoryTeam = AgeCategoryTeam::find($data['id']);
    $ageCategoryTeam->name = $data['name'];
    $ageCategoryTeam->order = $data['order'];
    $ageCategoryTeam->country_id = $data['country']['id'];
    $ageCategoryTeam->save();

    return $ageCategoryTeam;
  }

  /*
   * Delete age category team
   *
   * @return response
   */
  public function deleteAgeCategoryTeam($ageCategoryTeamId)
  {
    $ageCategoryTeam = AgeCategoryTeam::find($ageCategoryTeamId);
    if($ageCategoryTeam->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple age category teams
   *
   * @return response
   */
  public function deleteAgeCategoryTeams($ageCategoryTeamIds = [])
  {
    AgeCategoryTeam::whereIn('id', $ageCategoryTeamIds)->delete();
    return true;
  }

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $this->saveAgeCategories($data);
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($data)
  {
    $countries = Country::all();

    return ['countries' => $countries];
  }

  /*
   * Save age categories
   *
   * @return response
   */
  public function saveAgeCategories($data)
  {
    $websiteId = $data['websiteId'];
    $ageCategories = $data['ageCategories'];

    $existingAgeCategoriesId = $this->getAllAgeCategoryIds($websiteId);

    $ageCategoriesIds = [];
    for($i=0; $i<count($ageCategories); $i++) {
      $ageCategoryData = $ageCategories[$i];
      $ageCategoryData['order'] = $i + 1;
      if($ageCategoryData['id'] == '') {
        $ageCategory = $this->insertAgeCategory($websiteId, $ageCategoryData);
      } else {
        $ageCategory = $this->updateAgeCategory($ageCategoryData);
      }
      $this->saveAgeCategoryTeams($ageCategoryData['teams'], $ageCategory->id, $websiteId);
      $ageCategoriesIds[] = $ageCategory->id;
    }

    $deleteAgeCategoriesId = array_diff($existingAgeCategoriesId, $ageCategoriesIds);

    $this->deleteAgeCategories($deleteAgeCategoriesId);
  }

  /*
   * Save age category teams
   *
   * @return response
   */
  public function saveAgeCategoryTeams($teams, $ageCategoryId, $websiteId)
  {
    $existingAgeCategoryTeamsId = $this->getAllAgeCategoryTeamIds($ageCategoryId, $websiteId);

    $ageCategoryTeamIds = [];
    for($i=0; $i<count($teams); $i++) {
      $teamData = $teams[$i];
      $teamData['order'] = $i + 1;

      if($teamData['id'] == '') {
        $ageCategoryTeam = $this->insertAgeCategoryTeam($ageCategoryId, $websiteId, $teamData);
      } else {
        $ageCategoryTeam = $this->updateAgeCategoryTeam($teamData);
      }
      $ageCategoryTeamIds[] = $ageCategoryTeam->id;
    }

    $deleteAgeCategoryTeamsId = array_diff($existingAgeCategoryTeamsId, $ageCategoryTeamIds);

    $this->deleteAgeCategoryTeams($deleteAgeCategoryTeamsId);
  }
}
