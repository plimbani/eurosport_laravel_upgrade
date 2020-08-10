<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

/**
 *
 * APIs for project configurations.
 */
class ProjectConfigurationController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a project configurations.
     */
    public function getProjectConfigurations()
    {
        if(config('config-variables.current_layout') === 'tmp') {
            return response()->json([
                'android_app_version' => config('config-variables.APP_VERSION.tmp.android'),
                'ios_app_version'     => config('config-variables.APP_VERSION.tmp.ios'),
                'enable_testfairy_android' => config('config-variables.TESTFAIRY.tmp.android.enable_testfairy'),
                'enable_testfairy_video_capture_android' => config('config-variables.TESTFAIRY.tmp.android.enable_testfairy_video'),
                'enable_testfairy_feedback_android' => config('config-variables.TESTFAIRY.tmp.android.enable_testfairy_feedback'),
                'enable_testfairy_ios' => config('config-variables.TESTFAIRY.tmp.ios.enable_testfairy'),
                'enable_testfairy_video_capture_ios' => config('config-variables.TESTFAIRY.tmp.ios.enable_testfairy_video'),
                'enable_testfairy_feedback_ios' => config('config-variables.TESTFAIRY.tmp.ios.enable_testfairy_feedback'),
            ]);
        } else if(config('config-variables.current_layout') === 'commercialisation') {
            return response()->json([
                'android_app_version'     => config('config-variables.APP_VERSION.emm.android'),
                'ios_app_version'     => config('config-variables.APP_VERSION.emm.ios'),
                'enable_testfairy_android' => config('config-variables.TESTFAIRY.emm.android.enable_testfairy'),
                'enable_testfairy_video_capture_android' => config('config-variables.TESTFAIRY.emm.android.enable_testfairy_video'),
                'enable_testfairy_feedback_android' => config('config-variables.TESTFAIRY.emm.android.enable_testfairy_feedback'),
                'enable_testfairy_ios' => config('config-variables.TESTFAIRY.emm.ios.enable_testfairy'),
                'enable_testfairy_video_capture_ios' => config('config-variables.TESTFAIRY.emm.ios.enable_testfairy_video'),
                'enable_testfairy_feedback_ios' => config('config-variables.TESTFAIRY.emm.ios.enable_testfairy_feedback'),
            ]);
        }
    }
}