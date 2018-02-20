<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\UploadMediaContract;

class UploadMediaController extends BaseController
{
    public function __construct(UploadMediaContract $upMediaContract)
    {
        $this->upMediaContract = $upMediaContract;
    }

		public function uploadImage(Request $request) {
		  return $this->upMediaContract->uploadImage($request);
		}
}
