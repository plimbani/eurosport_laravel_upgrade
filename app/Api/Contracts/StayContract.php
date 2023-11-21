<?php

namespace Laraspace\Api\Contracts;

interface StayContract
{
    /*
   * Save save staypage data
   *
   * @return response
   */
    public function saveStayPageData($request);

    /*
     * Get stay page data
     *
     * @return response
     */
    public function getStayPageData($websiteId);
}
