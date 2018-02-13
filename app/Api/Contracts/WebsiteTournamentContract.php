<?php

namespace Laraspace\Api\Contracts;

interface WebsiteTournamentContract
{
  /*
   * Save save website tournament data
   *
   * @return response
   */
  public function saveWebsiteTournamentPageData($request);

  /*
   * Get website tournament data
   *
   * @return response
   */
  public function getWebsiteTournamentPageData($websiteId);
}