<?php

namespace Laraspace\Api\Contracts;

interface WebsiteVenueContract
{
	/*
   * Get locations
   *
   * @return response
   */
  public function getLocations($websiteId);

  /*
   * Save venue page data
   *
   * @return response
   */
  public function savePageData($request);
}
