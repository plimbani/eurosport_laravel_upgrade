<?php

namespace App\Api\Contracts;

interface UploadMediaContract
{
    /*
     * upload image
     *
     * @return response
     */
    public function uploadImage($request);
}
