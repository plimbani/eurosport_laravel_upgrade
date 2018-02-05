<?php

namespace Laraspace\Api\Contracts;

interface WebsiteContract
{
	/*
     * Get user accessible websites
     *
     * @return response
     */
    public function getUserAccessibleWebsites();

	/*
     * Save website data
     *
     * @return response
     */
    public function saveWebsiteData($request);
}
