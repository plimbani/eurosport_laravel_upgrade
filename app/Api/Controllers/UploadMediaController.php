<?php

namespace Laraspace\Api\Controllers;

// Need to define only contracts
use Laraspace\Api\Contracts\UploadMediaContract;
use Laraspace\Http\Requests\Image\UploadImageRequest;

class UploadMediaController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(UploadMediaContract $upMediaContract)
    {
        $this->upMediaContract = $upMediaContract;
    }

    /**
     * Upload image
     *
     * @Get("/uploadImage")
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={})
     */
    public function uploadImage(UploadImageRequest $request)
    {
        return $this->upMediaContract->uploadImage($request);
    }
}
