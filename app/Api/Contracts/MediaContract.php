<?php

namespace Laraspace\Api\Contracts;

interface MediaContract
{
	/*
     * Get photos
     *
     * @return response
     */
    public function getPhotos($websiteId);

    /*
     * Get documents
     *
     * @return response
     */
    public function getDocuments($websiteId);

    /*
     * Get media page data
     *
     * @return response
     */
    public function getPageData($websiteId);
}
