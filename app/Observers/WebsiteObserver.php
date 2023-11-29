<?php

namespace App\Observers;

use App\Models\Website;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;
use App\Traits\TrackActivitySection;

class WebsiteObserver
{
    use ManageActivityLog, AuthUserDetail, TrackActivitySection, ManageActivityNotification;

    /**
     * Listen to the Website created event.
     *
     * @return void
     */
    public function created(Website $website)
    {
        \Log::info('created');
    }

    /**
     * Listen to the Website updated event.
     *
     * @return void
     */
    public function updated(Website $website)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();
        $section = $this->getWebsiteSection($website);

        $websiteData = [];
        $websiteData['website_id'] = $website->id;
        $websiteData['notification_id'] = $this->getNotificationId($userObj);
        $websiteData['subject_id'] = $website->id;
        $websiteData['subject_type'] = get_class($website);
        $websiteData['causer_id'] = $userObj->id;
        $websiteData['causer_type'] = get_class($userObj);
        $websiteData['description'] = $userObj->name.' '.'updated a website page.';
        $websiteData['page'] = 'Website';
        $websiteData['section'] = $section;
        $websiteData['action'] = 'updated';

        $this->saveActivityLog($websiteData);
    }

    /**
     * Listen to the Website deleted event.
     *
     * @return void
     */
    public function deleted(Website $website)
    {
        \Log::info('deleted');
    }
}
