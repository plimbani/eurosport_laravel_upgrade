<?php

namespace Laraspace\Api\Contracts;

interface VisitorContract
{
    /*
     * Get visitor page data
     *
     * @return response
     */
    public function savePageData($request);

    /*
     * Get visitor page data
     *
     * @return response
     */
    public function getPageData($websiteId);
}
