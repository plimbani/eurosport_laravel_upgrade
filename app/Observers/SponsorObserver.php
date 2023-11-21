<?php

namespace Laraspace\Observers;

use Laraspace\Models\Sponsor;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class SponsorObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Sponsor created event.
     *
     * @return void
     */
    public function created(Sponsor $sponsor)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $sponsorData = [];
        $sponsorData['website_id'] = $sponsor->website_id;
        $sponsorData['notification_id'] = $this->getNotificationId($userObj);
        $sponsorData['subject_id'] = $sponsor->id;
        $sponsorData['subject_type'] = get_class($sponsor);
        $sponsorData['causer_id'] = $userObj->id;
        $sponsorData['causer_type'] = get_class($userObj);
        $sponsorData['description'] = $userObj->name.' '.'added a new sponsor.';
        $sponsorData['page'] = 'Website';
        $sponsorData['section'] = 'Sponsors';
        $sponsorData['action'] = 'created';

        $this->saveActivityLog($sponsorData);
    }

    /**
     * Listen to the Sponsor updated event.
     *
     * @return void
     */
    public function updated(Sponsor $sponsor)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $sponsorData = [];
        $sponsorData['website_id'] = $sponsor->website_id;
        $sponsorData['notification_id'] = $this->getNotificationId($userObj);
        $sponsorData['subject_id'] = $sponsor->id;
        $sponsorData['subject_type'] = get_class($sponsor);
        $sponsorData['causer_id'] = $userObj->id;
        $sponsorData['causer_type'] = get_class($userObj);
        $sponsorData['description'] = $userObj->name.' '.'updated a sponsor.';
        $sponsorData['page'] = 'Website';
        $sponsorData['section'] = 'Sponsors';
        $sponsorData['action'] = 'updated';

        $this->saveActivityLog($sponsorData);
    }

    /**
     * Listen to the Sponsor deleted event.
     *
     * @return void
     */
    public function deleted(Sponsor $sponsor)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $sponsorData = [];
        $sponsorData['website_id'] = $sponsor->website_id;
        $sponsorData['notification_id'] = $this->getNotificationId($userObj);
        $sponsorData['subject_id'] = $sponsor->id;
        $sponsorData['subject_type'] = get_class($sponsor);
        $sponsorData['causer_id'] = $userObj->id;
        $sponsorData['causer_type'] = get_class($userObj);
        $sponsorData['description'] = $userObj->name.' '.'deleted a sponsor.';
        $sponsorData['page'] = 'Website';
        $sponsorData['section'] = 'Sponsors';
        $sponsorData['action'] = 'deleted';

        $this->saveActivityLog($sponsorData);
    }
}
