<?php
namespace Laraspace\Custom\Helper;

use Config;
use Storage;
use Intervention\Image\ImageManager;

class Image {
	 /*
   * Common function for uploading image
   *
   * @return response
   */
	static function uploadImage($imagePath, $imageString) {
	    $s3 = Storage::disk('s3');
	    $img = explode(',', $imageString);
	    if(count($img)>1) {
	      $imgData = base64_decode($img[1]);
	    }else{
	      return '';
	    }

	    $timeStamp = md5(microtime(true) . rand(10,99));

	    $path = $imagePath.$timeStamp.'.png';
	    $s3->put($path, $imgData);

	    return $timeStamp.'.png';
	}

	 /*
   * Common function for uploading FILE Object image
   *
   * @return response
   */
	static function uploadImageUsingFileObj($image, $imagePath) {
		$s3 = Storage::disk('s3');
		$imageFileName = md5(microtime(true) . rand(10,99)) . '.' . $image->getClientOriginalExtension();
		$path = $imagePath.$imageFileName;
		$s3->put($path, file_get_contents($image), 'public');
		return $path;
	}

	/*
   * Common function for uploading IMAGE to local
   *
   * @return response
   */
	static function createTempImage($image) {
		$imageManager = new ImageManager();
		$tempImagePath = Config::get('wot.tempImagePath');
    $filename = md5(microtime(true) . rand(10,99)) . '.' . $image->getClientOriginalExtension();
    $localpath  = $tempImagePath.$filename;
    $image = $imageManager->make($image->getRealPath());
    $image->save($localpath);
    return $filename;
  }
}