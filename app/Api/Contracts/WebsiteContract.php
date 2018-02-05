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
}
