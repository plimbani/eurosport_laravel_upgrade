<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

use Laraspace\Api\Contracts\ProgramContract;
use Laraspace\Http\Requests\Program\StoreUpdateRequest;
use Laraspace\Http\Requests\Program\GetItineriesRequest;
use Laraspace\Http\Requests\Program\GetProgramPageDataRequest;

class ProgramController extends BaseController {
	/**
   * @var programContract
   */
  protected $programContract;

  /**
   * Create a new controller instance.
   *
   * @param ProgramContract $programContract
   */
  public function __construct(ProgramContract $programContract)
  {
  	$this->programContract = $programContract;
  }

  /**
   * Get all itineraries
   *
   * @param ProgramContract $programContract
   */
  public function getItineraries(GetItineriesRequest $request, $websiteId)
  {
    return $this->programContract->getItineraries($websiteId);
  }

  /**
   * Save program page data
   *
   * @param ProgramContract $programContract
   */
  public function saveProgramPageData(StoreUpdateRequest $request)
  {
    return $this->programContract->saveProgramPageData($request->all());
  }

  /**
   * Get program page data
   *
   * @param ProgramContract $programContract
   */
  public function getProgramPageData(GetProgramPageDataRequest $request, $websiteId)
  {
    return $this->programContract->getProgramPageData($websiteId);
  }
}