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
    $teamArray = [];
    $ageCategoryOrder = 1;
    $ageCategoryTeamOrder = 1;

    Excel::load($file->getRealPath(), function($reader) use(&$teamArray) {
      // Select
      $reader->select(array('age_category', 'team_name', 'country'))->get();

      // Loop through all sheets
      $reader->each(function($sheet) use(&$teamArray) {
        // $records = $sheet->toArray();
        // $teamArray = array_merge($teamArray, $records);
        // Loop through all rows
        $sheet->each(function($row) use(&$teamArray) {
          if($row->has('age_category') && $row->has('team_name') && $row->has('country')) {
            $teamRow = [
              'team_name' => $row->team_name,
              'country' => $row->country,
            ];
            $teamArray[$row->age_category] = $teamRow;
          }
        });
      });
    }, 'ISO-8859-1');

    // Delete all teams by website id
    $this->websiteTeamRepo->deleteAgeCategoryTeamsByWebsiteId($websiteId);

    // Delete age categories by website id
    $this->websiteTeamRepo->deleteAgeCategoriesByWebsiteId($websiteId);

    foreach($teamArray as $ageCategory => $teamData) {
      $ageCategoryData = [
        'name' => $ageCategory,
        'order' => $ageCategoryOrder,
      ];

      $ageCategory = $this->websiteTeamRepo->insertAgeCategory($websiteId, $ageCategoryData);

      foreach($teamData as $teamRow) {


        // $this->websiteTeamRepo->insertAgeCategoryTeam($ageCategory->id, $websiteId, $data);
      }
    }

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
