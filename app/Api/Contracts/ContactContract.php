<?php

namespace Laraspace\Api\Contracts;

interface ContactContract
{
    /*
     * Get contact details
     *
     * @return response
     */
    public function getContactDetails($websiteId);

	/*
     * Save contact details
     *
     * @return response
     */
    public function saveContactDetails($data);

    /*
     * Save inquiry details
     *
     * @return response
     */
    public function saveInquiryDetails($data);
}
