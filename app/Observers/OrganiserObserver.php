<?php

namespace Laraspace\Observers;

use Laraspace\Models\Organiser;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class OrganiserObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Organiser created event.
     *
     * @param  \Laraspace\Models\Organiser $organiser
     * @return void
     */
    public function created(Organiser $organiser)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $organiserData = [];
      $organiserData['website_id'] = $organiser->website_id;
      $organiserData['notification_id'] = $this->getNotificationId($userObj);
      $organiserData['subject_id'] = $organiser->id;
      $organiserData['subject_type'] = get_class($organiser);
      $organiserData['causer_id'] = $userObj->id;
      $organiserData['causer_type'] = get_class($userObj);
      $organiserData['description'] = $userObj->name .' '. 'added a new organiser.';
      $organiserData['page'] = 'Homepage';
      $organiserData['section'] = 'Organisers';
      $organiserData['action'] = 'created';

      $this->saveActivityLog($organiserData);
    }

    /**
     * Listen to the Organiser updated event.
     *
     * @param  \Laraspace\Models\Organiser $organiser
     * @return void
     */
    public function updated(Organiser $organiser)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $organiserData = [];
      $organiserData['website_id'] = $organiser->website_id;
      $organiserData['notification_id'] = $this->getNotificationId($userObj);
      $organiserData['subject_id'] = $organiser->id;
      $organiserData['subject_type'] = get_class($organiser);
      $organiserData['causer_id'] = $userObj->id;
      $organiserData['causer_type'] = get_class($userObj);
      $organiserData['description'] = $userObj->name .' '. 'updated a organiser.';
      $organiserData['page'] = 'Homepage';
      $organiserData['section'] = 'Organisers';
      $organiserData['action'] = 'updated';

      $this->saveActivityLog($organiserData);
    }

    /**
     * Listen to the Organiser deleted event.
     *
     * @param  \Laraspace\Models\Organiser $organiser
     * @return void
     */
    public function deleted(Organiser $organiser)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $organiserData = [];
      $organiserData['website_id'] = $organiser->website_id;
      $organiserData['notification_id'] = $this->getNotificationId($userObj);
      $organiserData['subject_id'] = $organiser->id;
      $organiserData['subject_type'] = get_class($organiser);
      $organiserData['causer_id'] = $userObj->id;
      $organiserData['causer_type'] = get_class($userObj);
      $organiserData['description'] = $userObj->name .' '. 'deleted a organiser.';
      $organiserData['page'] = 'Homepage';
      $organiserData['section'] = 'Organisers';
      $organiserData['action'] = 'deleted';

      $this->saveActivityLog($organiserData);
    }
}