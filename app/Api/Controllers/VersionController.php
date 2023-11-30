<?php

namespace App\Api\Controllers;

/**
 * APK Version.
 */
class VersionController extends BaseController
{
    /**
     * Get the APK version
     */
    public function apkVersion()
    {
        return response()->json([
            'android_app_version' => env('ANDROID_APP_VERSION'),
            'ios_app_version' => env('IOS_APP_VERSION'),
        ]);
    }
}
