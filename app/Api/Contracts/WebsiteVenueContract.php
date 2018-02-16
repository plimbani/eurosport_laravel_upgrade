<?php

namespace Laraspace\Api\Contracts;

interface WebsiteVenueContract
{
  /*
   * Save venue page data
   *
   * @return response
   */
  public function saveVenuePageData($request);

    /*
     * Get All Venues
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function index($tournamentId);

    /*
     * Create New Venues
     *
     * @param  array $api_key,$data
     * @return response
     */

    public function create($request);

    /*
     * Edit Venues
     *
     * @param  array $api_key,$tournament_id,$data
     * @return response
     */
    public function edit($request);

    /*
     * Delete Venues
     *
     * @param  array $api_key,$tournament_id
     * @return response
     */

    public function delete($request);
}
