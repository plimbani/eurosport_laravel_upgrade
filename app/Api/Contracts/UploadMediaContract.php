<?php

namespace Laraspace\Api\Contracts;

interface UploadMediaContract
{
    /*
     * upload image
     *
     * @return response
     */
    public function uploadImage($request);
}
