<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\WebsiteSetting;

class WebsiteSettingRepository
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Save settings in table
     * @param array $settings
     * @return
     */
    public function saveSettings($settings)
    {
        $websiteSettings = WebsiteSetting::firstOrCreate(['key_field' => $settings['key_field']]);
        $websiteSettings->value_field = json_encode($settings['value_field']);
        $websiteSettings->save();
    }
    
    /**
     * Fetch settings
     * @param string $type
     * @return array
     */
    public function getSettings($type)
    {
        $websiteSettings = WebsiteSetting::select('value_field')->where(['key_field' => $type])->first();
        
        return json_decode($websiteSettings->value_field);
    }
}
