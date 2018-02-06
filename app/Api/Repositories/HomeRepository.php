<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Statistic;
use Laraspace\Models\Organiser;

class HomeRepository
{
  /**
   * Create a new controller instance.
   */
  public function __construct()
  {
    
  }

  /*
   * Get all statistics
   *
   * @return response
   */
  public function getAllStatistics($websiteId)
  {
    $statistics = Statistic::where('website_id', $websiteId)->get();
    return $statistics;
  }

  /*
   * Get statistics count
   *
   * @return response
   */
  public function getOrderForNewStatistic($websiteId)
  {
    $count = Statistic::where('website_id', $websiteId)->count();
    return $count + 1;
  }

  /*
   * Insert statistic
   *
   * @return response
   */
  public function insertStatistic($websiteId, $data)
  {
    $statistic = new Statistic();
    $statistic->website_id = $websiteId;
    $statistic->content = $data['statistic'];
    $statistic->order = $this->getOrderForNewStatistic($websiteId);
    $statistic->save();

    return $statistic;
  }

  /*
   * Update statistic
   *
   * @return response
   */
  public function updateStatistic($data)
  {
    $statistic = Statistic::find($data['id']);
    $statistic->content = $data['statistic'];
    $statistic->save();

    return $statistic;
  }

  /*
   * Update statistic
   *
   * @return response
   */
  public function deleteStatistic($statisticId)
  {
    $statistic = Statistic::find($statisticId);
    if($statistic->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Get all organisers
   *
   * @return response
   */
  public function getAllOrganisers($websiteId)
  {
    $organisers = Organiser::where('website_id', $websiteId)->get();
    return $organisers;
  }
}
