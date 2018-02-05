<?php

namespace Laraspace\Api\Contracts;

interface WebsiteContract
{
	/*
     * Get all websites
     *
     * @return response
     */
    public function getUserAccessibleWebsites();
}
