<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

use Laraspace\Api\Contracts\StayContract;
use Laraspace\Http\Requests\Stay\StoreUpdateRequest;
use Laraspace\Http\Requests\Stay\GetWebsiteStayPageDataRequest;

class StayController extends BaseController {

	/**
   * @var stayContract
   */
  protected $stayContract;

  /**
   * Create a new controller instance.
   *
   * @param StayContract $stayContract
   */
  public function __construct(StayContract $stayContract)
  {
  	$this->stayContract = $stayContract;
  }

  /*
   * Save staypage data
   *
   * @return response
   */	
	public function saveStayPageData(StoreUpdateRequest $request)
	{
    return $this->stayContract->saveStayPageData($request->all());
	}

  /**
   * Get stay page data
   *
   * @Get("/getStayPageData")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getStayPageData(GetWebsiteStayPageDataRequest $request, $websiteId)
  {
    return $this->stayContract->getStayPageData($websiteId);
  }
}