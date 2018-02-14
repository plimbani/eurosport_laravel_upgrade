<?php

namespace Laraspace\Api\Services;

use Excel;
use Laraspace\Api\Contracts\WebsiteTeamContract;
use Laraspace\Api\Repositories\WebsiteTeamRepository;

class WebsiteTeamService implements WebsiteTeamContract
{
  /**
   * @var WebsiteTeamRepository
   */
  protected $websiteTeamRepo;

	/**
   *  Success message
   */
  const SUCCESS_MSG = 'Data Sucessfully inserted';

  /**
   *  Error message
   */
  const ERROR_MSG = 'Error in Data';

  /**
   * Create a new controller instance.
   *
   * @param WebsiteTeamRepository $websiteTeamRepo
   */
  public function __construct(WebsiteTeamRepository $websiteTeamRepo)
  {
    $this->websiteTeamRepo = $websiteTeamRepo;
  }

  /*
   * Get age categories
   *
   * @return response
   */
  public function getAgeCategories($websiteId)
  {
    $data = $this->websiteTeamRepo->getAllAgeCategories($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($data)
  {
    $data = $this->websiteTeamRepo->getPageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $data = $this->websiteTeamRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Import age category and team data
   *
   * @return response
   */
  public function importAgeCategoryAndTeamData($request)
  {
    $websiteId = $request->get('websiteId');
    $file = $request->file('team_upload');
    $ageCategories = [];
    $processedAgeCategories = [];
    $countries = $this->websiteTeamRepo->getCountriesKeyByCode();

    Excel::load($file->getRealPath(), function($reader) use(&$ageCategories, $countries, &$processedAgeCategories) {
      // Select
      $reader->select(array('age_category', 'team_name', 'country'))->get();

      // Loop through all sheets
      $reader->each(function($sheet) use(&$ageCategories, $countries, &$processedAgeCategories) {
        // Loop through all rows
        $sheet->each(function($row) use(&$ageCategories, $countries, &$processedAgeCategories) {
          if($row->has('age_category') && $row->has('team_name') && $row->has('country')) {
            $country = isset($countries[$row->country]) ? $countries[$row->country] : null;
            if($country !== null) {
              if(!in_array($row->age_category, $processedAgeCategories)) {
                $processedAgeCategories[] = $row->age_category;
                $ageCategories[count($processedAgeCategories) - 1] = [
                  'id' => '',
                  'name' => $row->age_category,
                  'teams' => [],
                ];
              }
              $ageCategoryKey = array_search($row->age_category, $processedAgeCategories);
              $teamRow = [
                'id' => '',
                'name' => $row->team_name,
                'country' => $country,
              ];
              $ageCategories[$ageCategoryKey]['teams'][] = $teamRow;
            }
          }
        });
      });
    }, 'ISO-8859-1');

    $data = ['ageCategories' => $ageCategories];

    // // Delete all teams by website id
    // $this->websiteTeamRepo->deleteAgeCategoryTeamsByWebsiteId($websiteId);

    // // Delete age categories by website id
    // $this->websiteTeamRepo->deleteAgeCategoriesByWebsiteId($websiteId);

    // // update age category and teams
    // $this->updateAgeCategoryAndTeams($ageCategories, $websiteId);

    // // Get all age categories
    // $allAgeCategories = $this->websiteTeamRepo->getAllAgeCategories($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Update age category and team data
   *
   * @return response
   */
  public function updateAgeCategoryAndTeams($teamArray, $websiteId)
  {
    $ageCategoryOrder = 1;
    $ageCategoryTeamOrder = 1;

    $countries = $this->websiteTeamRepo->getCountriesKeyByCode();

    foreach($teamArray as $ageCategory => $teamData) {
      $ageCategoryData = [
        'name' => $ageCategory,
        'order' => $ageCategoryOrder,
      ];

      $ageCategory = $this->websiteTeamRepo->insertAgeCategory($websiteId, $ageCategoryData);

      foreach($teamData as $teamRow) {
        $countryId = isset($countries[$teamRow['country']]) ? $countries[$teamRow['country']]['id'] : 0;
        if($countryId == 0) {
          return;
        }
        $team = [
          'name' => $teamRow['team_name'],
          'country_id' => $countryId,
          'order' => $ageCategoryTeamOrder,
        ];
        $this->websiteTeamRepo->insertAgeCategoryTeam($ageCategory->id, $websiteId, $team);
        $ageCategoryTeamOrder++;
      }

      $ageCategoryOrder++;
    }
  }
}
