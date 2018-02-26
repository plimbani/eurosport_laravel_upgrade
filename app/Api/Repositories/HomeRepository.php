<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Statistic;
use Laraspace\Models\Organiser;
use Laraspace\Custom\Helper\Image;
use Laraspace\Api\Services\PageService;

class HomeRepository
{
  /**
   * @var AWS URL
   */
  protected $AWSUrl;

  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * @var Page name
   */
  protected $pageName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->AWSUrl = getenv('S3_URL');
    $this->pageService = $pageService;
    $this->pageName = 'home';
  }

  /*
   * Get all statistics
   *
   * @return response
   */
  public function getAllStatistics($websiteId)
  {
    $statistics = Statistic::where('website_id', $websiteId)->orderBy('order')->get();
    return $statistics;
  }

  /*
   * Get all statistic ids
   *
   * @return response
   */
  public function getAllStatisticIds($websiteId)
  {
    $statisticIds = Statistic::where('website_id', $websiteId)->pluck('id')->toArray();
    return $statisticIds;
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
    $statistic->content = $data['content'];
    $statistic->order = $data['order'];
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
    $statistic->content = $data['content'];
    $statistic->order = $data['order'];
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
   * Delete multiple statistics
   *
   * @return response
   */
  public function deleteStatistics($statisticIds = [])
  {
    Statistic::whereIn('id', $statisticIds)->get()->each(function($statistic) {
      $statistic->delete();
    });

    return true;
  }

  /*
   * Get all organisers
   *
   * @return response
   */
  public function getAllOrganisers($websiteId)
  {
    $organisers = Organiser::where('website_id', $websiteId)->orderBy('order')->get();

    return $organisers;
  }

  /*
   * Get all statistic ids
   *
   * @return response
   */
  public function getAllOrganiserIds($websiteId)
  {
    $organiserIds = Organiser::where('website_id', $websiteId)->pluck('id')->toArray();
    return $organiserIds;
  }

  /*
   * Insert organiser
   *
   * @return response
   */
  public function insertOrganiser($websiteId, $data)
  {
    $organiser = new Organiser();
    $organiser->website_id = $websiteId;
    $organiser->name = $data['name'];
    $organiser->order = $data['order'];
    $organiser->logo = $data['logo'];
    $organiser->save();

    return $organiser;
  }

  /*
   * Update organiser
   *
   * @return response
   */
  public function updateOrganiser($data)
  {
    $organiser = Organiser::find($data['id']);
    $organiser->name = $data['name'];
    $organiser->order = $data['order'];
    $organiser->logo = $data['logo'];
    $organiser->save();

    return $organiser;
  }

  /*
   * Update organiser
   *
   * @return response
   */
  public function deleteOrganiser($organiserId)
  {
    $organiser = Organiser::find($organiserId);
    if($organiser->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple organisers
   *
   * @return response
   */
  public function deleteOrganisers($organiserIds = [])
  {
    Organiser::whereIn('id', $organiserIds)->get()->each(function($organiser) {
      $organiser->delete();
    });

    return true;
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $pageDetail = array();
    $pageDetail['name'] = $this->pageName;
    $pageDetail['content'] = $data['introduction_text'];
    $meta = array();
    // Upload hero image
    $meta['hero_image'] = basename(parse_url($data['hero_image'])['path']);
    // Upload welcome image
    $meta['welcome_image'] = basename(parse_url($data['welcome_image'])['path']);;
    $pageDetail['meta'] = $meta;

    $this->pageService->updatePageDetails($pageDetail, $data['websiteId']);
    $this->saveStatistics($data);
    $this->saveOrganisers($data);
  }

  /*
   * Save statistics
   *
   * @return response
   */
  public function saveStatistics($data)
  {
    $websiteId = $data['websiteId'];
    $statistics = $data['statistics'];

    $existingStatisticsId = $this->getAllStatisticIds($websiteId);

    $statisticIds = [];
    for($i=0; $i<count($statistics); $i++) {
      $statisticData = $statistics[$i];
      $statisticData['order'] = $i + 1;
      if($statisticData['id'] == '') {
        $statistic = $this->insertStatistic($websiteId, $statisticData);
      } else {
        $statistic = $this->updateStatistic($statisticData);
      }
      $statisticIds[] = $statistic->id;
    }

    $deleteStatisticsId = array_diff($existingStatisticsId, $statisticIds);

    $this->deleteStatistics($deleteStatisticsId);
  }

  /*
   * Save organisers
   *
   * @return response
   */
  public function saveOrganisers($data)
  {
    $websiteId = $data['websiteId'];
    $organisers = $data['organiserLogos'];

    $existingOrganisersId = $this->getAllOrganiserIds($websiteId);

    $organiserIds = [];
    for($i=0; $i<count($organisers); $i++) {
      $organiserData = $organisers[$i];
      $organiserData['order'] = $i + 1;

      // Upload image
      $organiserData['logo'] = basename(parse_url($organiserData['logo'])['path']);

      if($organiserData['id'] == '') {
        $organiser = $this->insertOrganiser($websiteId, $organiserData);
      } else {
        $organiser = $this->updateOrganiser($organiserData);
      }
      $organiserIds[] = $organiser->id;
    }

    $deleteOrganisersId = array_diff($existingOrganisersId, $organiserIds);

    $this->deleteOrganisers($deleteOrganisersId);
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function getPageData($websiteId)
  {
    return $this->pageService->getPageDetails($this->pageName, $websiteId);
  }
}
