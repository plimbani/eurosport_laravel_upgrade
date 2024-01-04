<?php

namespace App\Observers;

use App\Models\Itinerary;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class ItineraryObserver
{
    use AuthUserDetail, ManageActivityLog, ManageActivityNotification;

    /**
     * Listen to the Itinerary created event.
     *
     * @return void
     */
    public function created(Itinerary $itinerary)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $itineraryData = [];
        $itineraryData['website_id'] = $itinerary->website_id;
        $itineraryData['notification_id'] = $this->getNotificationId($userObj);
        $itineraryData['subject_id'] = $itinerary->id;
        $itineraryData['subject_type'] = get_class($itinerary);
        $itineraryData['causer_id'] = $userObj->id;
        $itineraryData['causer_type'] = get_class($userObj);
        $itineraryData['description'] = $userObj->name.' '.'added a new itinerary.';
        $itineraryData['page'] = 'Program';
        $itineraryData['section'] = 'Itinerary';
        $itineraryData['action'] = 'created';

        $this->saveActivityLog($itineraryData);
    }

    /**
     * Listen to the Itinerary updated event.
     *
     * @return void
     */
    public function updated(Itinerary $itinerary)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $itineraryData = [];
        $itineraryData['website_id'] = $itinerary->website_id;
        $itineraryData['notification_id'] = $this->getNotificationId($userObj);
        $itineraryData['subject_id'] = $itinerary->id;
        $itineraryData['subject_type'] = get_class($itinerary);
        $itineraryData['causer_id'] = $userObj->id;
        $itineraryData['causer_type'] = get_class($userObj);
        $itineraryData['description'] = $userObj->name.' '.'updated a itinerary.';
        $itineraryData['page'] = 'Program';
        $itineraryData['section'] = 'Itinerary';
        $itineraryData['action'] = 'updated';

        $this->saveActivityLog($itineraryData);
    }

    /**
     * Listen to the Itinerary deleted event.
     *
     * @return void
     */
    public function deleted(Itinerary $itinerary)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $itineraryData = [];
        $itineraryData['website_id'] = $itinerary->website_id;
        $itineraryData['notification_id'] = $this->getNotificationId($userObj);
        $itineraryData['subject_id'] = $itinerary->id;
        $itineraryData['subject_type'] = get_class($itinerary);
        $itineraryData['causer_id'] = $userObj->id;
        $itineraryData['causer_type'] = get_class($userObj);
        $itineraryData['description'] = $userObj->name.' '.'deleted a itinerary.';
        $itineraryData['page'] = 'Program';
        $itineraryData['section'] = 'Itinerary';
        $itineraryData['action'] = 'deleted';

        $this->saveActivityLog($itineraryData);
    }
}
