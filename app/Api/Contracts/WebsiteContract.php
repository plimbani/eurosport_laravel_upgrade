<?php

namespace Laraspace\Api\Contracts;

interface WebsiteContract
{
    /*
     * Get all websites
     *
     * @return websites array
     */
    public function index();
	
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

    /*
     * Get website details
     *
     * @return response
     */
    public function websiteSummary($request);

    /*
     * Get website all colours
     *
     * @return response
     */

    public function getWebsiteCustomisationOptions();

    /*
     * Get image path
     *
     * @return response
     */
    public function getImagePath();

    /*
     * Get website default pages
     *
     * @return response
     */
    public function getWebsiteDefaultPages();

    /*
     * Get sponsors
     *
     * @return response
     */
    public function getSponsors($websiteId);

    /*
     * Get website details
     *
     * @return response
     */
    public function getWebsiteDetails($websiteId);
}
