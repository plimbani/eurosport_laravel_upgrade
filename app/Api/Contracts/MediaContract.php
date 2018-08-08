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
     * Save media page data
     *
     * @return response
     */
    public function savePageData($request);

    /*
     * Upload media photo
     *
     * @return response
     */
    public function uploadMediaPhoto($request);

    /*
     * Upload document
     *
     * @return response
     */
    public function uploadDocument($request);
}
