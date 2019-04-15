<?php

namespace Laraspace\Api\Services\Commercialisation;

use Laraspace\Api\Contracts\Commercialisation\WebsiteSettingsContract;
use Laraspace\Api\Repositories\WebsiteSettingRepository;

class WebsiteSettingsService implements WebsiteSettingsContract
{

    /**
     * @var WebsiteRepository
     */
    protected $websiteRepo;

    /**
     * Create a new controller instance.
     * @param WebsiteSettingsRepository $websiteRepo
     */
    public function __construct(WebsiteSettingRepository $websiteRepo)
    {
        $this->websiteRepo = $websiteRepo;
    }

    public function saveSettings($settingFields)
    {
        return $this->websiteRepo->saveSettings($settingFields);
    }
    
    public function getSettings($type)
    {
        return $this->websiteRepo->getSettings($type);
    }
}
