<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\WebsiteContract;

class WebsiteService implements WebsiteContract
{
	public function __construct()
  {
		// $this->venueRepoObj = new \Laraspace\Api\Repositories\VenueRepository();
  }

  /*
   * Get all websites
   *
   * @param  array $api_key,$state,$type
   * @return response
   */
  public function getUserAccessibleWebsites()
  {
    // Here we send Status Code and Messages
    // $data = $this->venueRepoObj->getAllVenues($tournamentId);

    // if ($data) {
    //   return ['status_code' => '200', 'data' => $data];
    // }

    // return ['status_code' => '505', 'message' => self::ERROR_MSG];
  }
}
