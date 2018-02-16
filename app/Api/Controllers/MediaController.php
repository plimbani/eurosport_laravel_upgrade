<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\MediaContract;

/**
 * Media description.
 *
 * @Resource("pages")
 *
 */
class MediaController extends BaseController
{
	/**
   * @var MediaContract
   */
  protected $mediaContract;

  /**
   * Create a new controller instance.
   *
   * @param MediaContract $mediaContract
   */
  public function __construct(MediaContract $mediaContract)
  {
  	$this->mediaContract = $mediaContract;
  }

  /**
   * Get all photos
   *
   * Get a JSON representation of all the photos.
   *
   * @Get("/getPhotos")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getPhotos(Request $request, $websiteId)
  {
    return $this->mediaContract->getPhotos($websiteId);
  }

  /**
   * Get all documents
   *
   * Get a JSON representation of all the documents
   *
   * @Get("/getDocuments")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getDocuments(Request $request, $websiteId)
  {
    return $this->mediaContract->getDocuments($websiteId);
  }

  /**
   * Save page data
   *
   * @Get("/savePageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function savePageData(Request $request)
  {
    return $this->mediaContract->savePageData($request);
  }
}
