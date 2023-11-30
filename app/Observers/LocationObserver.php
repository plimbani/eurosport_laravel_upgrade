<?php

namespace App\Observers;

use App\Models\Location;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class LocationObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Location created event.
     *
     * @return void
     */
    public function created(Location $location)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $locationData = [];
        $locationData['website_id'] = $location->website_id;
        $locationData['notification_id'] = $this->getNotificationId($userObj);
        $locationData['subject_id'] = $location->id;
        $locationData['subject_type'] = get_class($location);
        $locationData['causer_id'] = $userObj->id;
        $locationData['causer_type'] = get_class($userObj);
        $locationData['description'] = $userObj->name.' '.'added a new location.';
        $locationData['page'] = 'Venue';
        $locationData['section'] = 'Locations';
        $locationData['action'] = 'created';

        $this->saveActivityLog($locationData);
    }

    /**
     * Listen to the Location updated event.
     *
     * @return void
     */
    public function updated(Location $location)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $locationData = [];
        $locationData['website_id'] = $location->website_id;
        $locationData['notification_id'] = $this->getNotificationId($userObj);
        $locationData['subject_id'] = $location->id;
        $locationData['subject_type'] = get_class($location);
        $locationData['causer_id'] = $userObj->id;
        $locationData['causer_type'] = get_class($userObj);
        $locationData['description'] = $userObj->name.' '.'updated a location.';
        $locationData['page'] = 'Venue';
        $locationData['section'] = 'Locations';
        $locationData['action'] = 'updated';

        $this->saveActivityLog($locationData);
    }

    /**
     * Listen to the Location deleted event.
     *
     * @return void
     */
    public function deleted(Location $location)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $locationData = [];
        $locationData['website_id'] = $location->website_id;
        $locationData['notification_id'] = $this->getNotificationId($userObj);
        $locationData['subject_id'] = $location->id;
        $locationData['subject_type'] = get_class($location);
        $locationData['causer_id'] = $userObj->id;
        $locationData['causer_type'] = get_class($userObj);
        $locationData['description'] = $userObj->name.' '.'deleted a location.';
        $locationData['page'] = 'Venue';
        $locationData['section'] = 'Locations';
        $locationData['action'] = 'deleted';

        $this->saveActivityLog($locationData);
    }
}
