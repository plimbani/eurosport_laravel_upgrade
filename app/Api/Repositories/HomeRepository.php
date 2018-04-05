<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Statistic;
use Laraspace\Models\Organiser;
use Laraspace\Custom\Helper\Image;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Api\Services\PageService;
use Illuminate\Support\Facades\Storage;

class HomeRepository
{
  use AuthUserDetail;

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
   * @var Sponsor logo image
   */
  protected $organiserLogoPath;

  /**
   * @var disk
   */
  protected $disk;

  /**
   * @var diskName
   */
  protected $diskName;

  /**
   * @var conversions
   */
  protected $organiserLogoConversions;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->AWSUrl = getenv('S3_URL');
    $this->pageService = $pageService;
    $this->pageName = 'home';
    $this->organiserLogoPath = config('wot.imagePath.organiser_logo');
    $this->diskName = config('filesystems.disks.s3.driver');
    $this->disk = Storage::disk($this->diskName);
    $this->organiserLogoConversions = config('image-conversion.conversions.organiser_logo');
  }

  /**
   * Destroy instance.
   *
   * @return void
   */
  public function __destruct()
  {
    unset($this->disk);
    unset($this->diskName);
    unset($this->organiserLogoPath);
    unset($this->organiserLogoConversions);
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
  public function insertStatistic($websiteId, $currentLoggedInUserId, $data)
  {
    $statistic = new Statistic();
    $statistic->website_id = $websiteId;
    $statistic->content = $data['content'];
    $statistic->order = $data['order'];
    $statistic->created_by = $currentLoggedInUserId;
    $statistic->save();

    return $statistic;
  }

  /*
   * Update statistic
   *
   * @return response
   */
  public function updateStatistic($currentLoggedInUserId, $data)
  {
    $statistic = Statistic::find($data['id']);
    $statistic->content = $data['content'];
    $statistic->order = $data['order'];
    if($statistic->isDirty()) {
      $statistic->updated_by = $currentLoggedInUserId;
      $statistic->save();
    }

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
  public function insertOrganiser($websiteId, $currentLoggedInUserId, $data)
  {
    $organiser = new Organiser();
    $organiser->website_id = $websiteId;
    $organiser->name = $data['name'];
    $organiser->order = $data['order'];
    $organiser->logo = $data['logo'];
    $organiser->created_by = $currentLoggedInUserId;
    $organiser->save();

    return $organiser;
  }

  /*
   * Update organiser
   *
   * @return response
   */
  public function updateOrganiser($currentLoggedInUserId, $data)
  {
    $organiser = Organiser::find($data['id']);
    $organiser->name = $data['name'];
    $organiser->order = $data['order'];
    $organiser->logo = $data['logo'];
    if($organiser->isDirty()) {
      $organiser->updated_by = $currentLoggedInUserId;
      $organiser->save();
    }

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
    if ($this->disk->exists($this->organiserLogoPath . $organiser->logo)) {
      $this->disk->delete($this->organiserLogoPath . $organiser->logo);
      foreach ($this->organiserLogoConversions as $key => $value) {
        if ($this->disk->exists($this->organiserLogoPath . $key . '/' . $organiser->logo)) {
          $this->disk->delete($this->organiserLogoPath . $key . '/' . $organiser->logo);
        }
      }
    }
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
      if ($this->disk->exists($this->organiserLogoPath . $organiser->logo)) {
        $this->disk->delete($this->organiserLogoPath . $organiser->logo);
        foreach ($this->organiserLogoConversions as $key => $value) {
          if ($this->disk->exists($this->organiserLogoPath . $key . '/' . $organiser->logo)) {
            $this->disk->delete($this->organiserLogoPath . $key . '/' . $organiser->logo);
          }
        }
      }
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
    $meta['welcome_image'] = basename(parse_url($data['welcome_image'])['path']);
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
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($statisticData['id'] == '') {
        $statistic = $this->insertStatistic($websiteId, $currentLoggedInUserId, $statisticData);
      } else {
        $statistic = $this->updateStatistic($currentLoggedInUserId, $statisticData);
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
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($organiserData['id'] == '') {
        $organiser = $this->insertOrganiser($websiteId, $currentLoggedInUserId, $organiserData);
      } else {
        $organiser = $this->updateOrganiser($currentLoggedInUserId, $organiserData);
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
