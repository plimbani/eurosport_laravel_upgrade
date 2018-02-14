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

    Excel::load($file->getRealPath(), function($reader) {
        // $totalSize  = $reader->getTotalRowsOfFile() - 1;

        // Loop through all sheets
        $reader->each(function($sheet) {
            // Loop through all rows
            $sheet->each(function($row) {
              print_r($row->age_category);
            });
        });
    }, 'ISO-8859-1');
exit;
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
