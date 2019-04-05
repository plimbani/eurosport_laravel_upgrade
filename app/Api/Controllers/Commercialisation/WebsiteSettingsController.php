<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Api\Repositories\WebsiteSettingRepository;
use Laraspace\Api\Services\Commercialisation\WebsiteSettingsService;

/**
 * Website Description.
 *
 * @Resource("websites")
 *
 */
class WebsiteSettingsController extends BaseController
{

    /**
     * @var WebsiteContract
     */
    protected $websiteSettings;

    /**
     * Create a new controller instance.
     *
     * @param WebsiteContract $websiteContract
     */
    public function __construct(WebsiteSettingsService $websiteSettService)
    {
        $this->websiteSettings = $websiteSettService;
    }

    /**
     * Save website settings
     * @param Request $request
     */
    public function saveSettings(Request $request)
    {
        try {
            $data = $request->all();
            $this->websiteSettings->saveSettings($data['setting_fields']);

            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => [],
                        'error' => [],
                        'message' => 'Settings saved successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Get website settings
     * @param Request $request
     * @return string
     */
    public function getSettings(Request $request)
    {
        try {
            $data = $request->all();
            $settings = $this->websiteSettings->getSettings($data['type']);

            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $settings,
                        'error' => [],
                        'message' => 'Settings has been fetched successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }
}
