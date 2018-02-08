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
    public function getWebsiteCustomisation();
}
