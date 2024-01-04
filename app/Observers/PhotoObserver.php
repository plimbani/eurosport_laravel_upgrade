<?php

namespace App\Observers;

use App\Models\Photo;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class PhotoObserver
{
    use AuthUserDetail, ManageActivityLog, ManageActivityNotification;

    /**
     * Listen to the Photo created event.
     *
     * @return void
     */
    public function created(Photo $photo)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $photoData = [];
        $photoData['website_id'] = $photo->website_id;
        $photoData['notification_id'] = $this->getNotificationId($userObj);
        $photoData['subject_id'] = $photo->id;
        $photoData['subject_type'] = get_class($photo);
        $photoData['causer_id'] = $userObj->id;
        $photoData['causer_type'] = get_class($userObj);
        $photoData['description'] = $userObj->name.' '.'added a new photo.';
        $photoData['page'] = 'Media';
        $photoData['section'] = 'Photo gallery';
        $photoData['action'] = 'created';

        $this->saveActivityLog($photoData);
    }

    /**
     * Listen to the Photo updated event.
     *
     * @return void
     */
    public function updated(Photo $photo)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $photoData = [];
        $photoData['website_id'] = $photo->website_id;
        $photoData['notification_id'] = $this->getNotificationId($userObj);
        $photoData['subject_id'] = $photo->id;
        $photoData['subject_type'] = get_class($photo);
        $photoData['causer_id'] = $userObj->id;
        $photoData['causer_type'] = get_class($userObj);
        $photoData['description'] = $userObj->name.' '.'updated a photo.';
        $photoData['page'] = 'Media';
        $photoData['section'] = 'Photo gallery';
        $photoData['action'] = 'updated';

        $this->saveActivityLog($photoData);
    }

    /**
     * Listen to the Photo deleted event.
     *
     * @return void
     */
    public function deleted(Photo $photo)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $photoData = [];
        $photoData['website_id'] = $photo->website_id;
        $photoData['notification_id'] = $this->getNotificationId($userObj);
        $photoData['subject_id'] = $photo->id;
        $photoData['subject_type'] = get_class($photo);
        $photoData['causer_id'] = $userObj->id;
        $photoData['causer_type'] = get_class($userObj);
        $photoData['description'] = $userObj->name.' '.'deleted a photo.';
        $photoData['page'] = 'Media';
        $photoData['section'] = 'Photo gallery';
        $photoData['action'] = 'deleted';

        $this->saveActivityLog($photoData);
    }
}
