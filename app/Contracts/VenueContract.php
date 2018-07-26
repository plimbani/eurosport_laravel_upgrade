<?php

namespace Laraspace\Contracts;

interface VenueContract
{
    /*
     * Get User Data
     *
     * @param  array $data
     * @return response
     */
     public function index();

     public function create($request);

     public function edit($request, $venueId);

}
