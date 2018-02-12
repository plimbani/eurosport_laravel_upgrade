<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\VisitorContract;

/**
 * Visitor description.
 *
 * @Resource("pages")
 *
 */
class VisitorController extends BaseController
{
	/**
   * @var VisitorContract
   */
  protected $visitorContract;

  /**
   * Create a new controller instance.
   *
   * @param VisitorContract $visitorContract
   */
  public function __construct(VisitorContract $visitorContract)
  {
  	$this->visitorContract = $visitorContract;
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
    return $this->visitorContract->savePageData($request);
  }

  /**
   * Get visitor page data
   *
   * @Get("/getVisitorPageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getPageData(Request $request, $websiteId)
  {
    return $this->visitorContract->getPageData($websiteId);
  }
  
}
