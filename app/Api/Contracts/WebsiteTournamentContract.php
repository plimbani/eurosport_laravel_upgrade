<?php

namespace Laraspace\Api\Contracts;

interface WebsiteTournamentContract
{
  /*
   * Save save website tournament data
   *
   * @return response
   */
  public function savePageData($request);

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getPageData($websiteId);
}