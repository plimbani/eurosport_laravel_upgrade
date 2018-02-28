<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

use Laraspace\Api\Contracts\ProgramContract;

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
  public function getItineraries(Request $request, $websiteId)
  {
    return $this->programContract->getItineraries($websiteId);
  }

  /**
   * Save program page data
   *
   * @param ProgramContract $programContract
   */
  public function saveProgramPageData(Request $request)
  {
    return $this->programContract->saveProgramPageData($request->all());
  }

  /**
   * Get program page data
   *
   * @param ProgramContract $programContract
   */
  public function getProgramPageData(Request $request, $websiteId)
  {
    return $this->programContract->getProgramPageData($websiteId);
  }
}