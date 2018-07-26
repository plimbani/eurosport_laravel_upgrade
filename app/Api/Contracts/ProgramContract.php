<?php

namespace Laraspace\Api\Contracts;

interface ProgramContract
{
	/*
   * Get all itineraries
   *
   * @return response
   */	
  public function getItineraries($websiteId);

	/*
   * Save program page data
   *
   * @return response
   */
  public function saveProgramPageData($data);

  /*
   * Get program page data
   *
   * @return response
   */
  public function getProgramPageData($websiteId);
}