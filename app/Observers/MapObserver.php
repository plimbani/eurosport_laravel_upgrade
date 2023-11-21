<?php

namespace Laraspace\Observers;

use Laraspace\Models\Map;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class MapObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Map created event.
     *
     * @return void
     */
    public function created(Map $map)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $mapData = [];
        $mapData['website_id'] = $map->website_id;
        $mapData['notification_id'] = $this->getNotificationId($userObj);
        $mapData['subject_id'] = $map->id;
        $mapData['subject_type'] = get_class($map);
        $mapData['causer_id'] = $userObj->id;
        $mapData['causer_type'] = get_class($userObj);
        $mapData['description'] = $userObj->name.' '.'added a new map marker.';
        $mapData['page'] = 'Venue';
        $mapData['section'] = 'Map';
        $mapData['action'] = 'created';

        $this->saveActivityLog($mapData);
    }

    /**
     * Listen to the Map updated event.
     *
     * @return void
     */
    public function updated(Map $map)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $mapData = [];
        $mapData['website_id'] = $map->website_id;
        $mapData['notification_id'] = $this->getNotificationId($userObj);
        $mapData['subject_id'] = $map->id;
        $mapData['subject_type'] = get_class($map);
        $mapData['causer_id'] = $userObj->id;
        $mapData['causer_type'] = get_class($userObj);
        $mapData['description'] = $userObj->name.' '.'updated a map marker.';
        $mapData['page'] = 'Venue';
        $mapData['section'] = 'Map';
        $mapData['action'] = 'updated';

        $this->saveActivityLog($mapData);
    }

    /**
     * Listen to the Map deleted event.
     *
     * @return void
     */
    public function deleted(Map $map)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $mapData = [];
        $mapData['website_id'] = $map->website_id;
        $mapData['notification_id'] = $this->getNotificationId($userObj);
        $mapData['subject_id'] = $map->id;
        $mapData['subject_type'] = get_class($map);
        $mapData['causer_id'] = $userObj->id;
        $mapData['causer_type'] = get_class($userObj);
        $mapData['description'] = $userObj->name.' '.'deleted a map marker.';
        $mapData['page'] = 'Venue';
        $mapData['section'] = 'Map';
        $mapData['action'] = 'deleted';

        $this->saveActivityLog($mapData);
    }
}
