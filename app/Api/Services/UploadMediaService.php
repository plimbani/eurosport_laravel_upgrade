<?php

namespace App\Api\Services;

use App\Api\Contracts\UploadMediaContract;
use App\Custom\Helper\Image;

class UploadMediaService implements UploadMediaContract
{
    /**
     * @var AWS URL
     */
    protected $getAWSUrl;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->getAWSUrl = getenv('S3_URL');
    }

    /*
     * Save media data
     *
     * @return response
     */
    public function uploadImage($request)
    {
        $this->getAWSUrl = getenv('S3_URL');
        $image = $request->file('image');
        $imagePath = $request->all()['imagePath'];
        $imagePath = str_replace($this->getAWSUrl, '', $imagePath);
        $imagePath = Image::uploadImageUsingFileObj($image, $imagePath);

        return $this->getAWSUrl.$imagePath;
    }
}
