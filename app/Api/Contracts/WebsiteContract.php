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
     * Upload website tournament logo
     *
     * @return response
     */
    public function uploadTournamentLogo($request);

    /*
     * Upload website social graphic
     *
     * @return response
     */
    public function uploadSocialGraphic($request);

    /*
     * Upload website sponsor image
     *
     * @return response
     */
    public function uploadSponsorImage($request);

    /*
     * Upload website hero image
     *
     * @return response
     */
    public function uploadHeroImage($request);

    /*
     * Upload welcome image
     *
     * @return response
     */
    public function uploadWelcomeImage($request);

    /*
     * Upload organiser image
     *
     * @return response
     */
    public function uploadOrganiserLogo($request);
    
    /*
     * Get website details
     *
     * @return response
     */
    public function getWebsiteDetails($websiteId);

}
