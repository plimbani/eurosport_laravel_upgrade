<?php

namespace Laraspace\Api\Contracts;

interface HomeContract
{
	/*
     * Get statistics
     *
     * @return response
     */
    public function getStatistics($websiteId);

    /*
     * Get organisers
     *
     * @return response
     */
    public function getOrganisers($websiteId);

    /*
     * Get home page data
     *
     * @return response
     */
    public function getPageData($websiteId);
}
