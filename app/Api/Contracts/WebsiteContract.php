<?php

namespace Laraspace\Api\Contracts;

interface WebsiteContract
{
    /*
     * Get all websites
     *
     * @return websites array
     */
    public function getAll();
	
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
