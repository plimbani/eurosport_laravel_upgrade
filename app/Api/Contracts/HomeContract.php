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
}
