<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\UploadMediaContract;

class UploadMediaController extends BaseController
{
	/**
   * Create a new controller instance.
   *
   * @param UploadMediaContract $upMediaContract
   */
  public function __construct(UploadMediaContract $upMediaContract)
  {
    $this->upMediaContract = $upMediaContract;
  }

  /**
   * Upload image
   *
   * @Get("/uploadImage")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
	public function uploadImage(Request $request) {
	  return $this->upMediaContract->uploadImage($request);
	}
}