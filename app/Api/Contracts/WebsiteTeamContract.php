<?php

namespace Laraspace\Api\Contracts;

interface WebsiteTeamContract
{
    /*
     * Get all age categories
     *
     * @return response
     */
    public function getAgeCategories($websiteId);

    /*
     * Save page data
     *
     * @return response
     */
    public function savePageData($request);

    /*
     * Get page data
     *
     * @return response
     */
    public function getPageData($request);
}
