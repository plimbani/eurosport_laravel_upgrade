<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

/**
 * APK Version.
 */
class VersionController extends BaseController
{
	/**
	 * Get the APK version
	 */
	public function apkVersion(){
        return response()->json([
            'apk_version' => env('APK_VERSION')
        ]);        
    }
}