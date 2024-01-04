<?php

namespace App\Observers;

use App\Models\AgeCategory;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class AgeCategoryObserver
{
    use AuthUserDetail, ManageActivityLog, ManageActivityNotification;

    /**
     * Listen to the AgeCategory created event.
     *
     * @return void
     */
    public function created(AgeCategory $ageCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryData = [];
        $ageCategoryData['website_id'] = $ageCategory->website_id;
        $ageCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryData['subject_id'] = $ageCategory->id;
        $ageCategoryData['subject_type'] = get_class($ageCategory);
        $ageCategoryData['causer_id'] = $userObj->id;
        $ageCategoryData['causer_type'] = get_class($userObj);
        $ageCategoryData['description'] = $userObj->name.' '.'added a new age category.';
        $ageCategoryData['page'] = 'Teams';
        $ageCategoryData['section'] = 'Age categories';
        $ageCategoryData['action'] = 'created';

        $this->saveActivityLog($ageCategoryData);
    }

    /**
     * Listen to the AgeCategory updated event.
     *
     * @return void
     */
    public function updated(AgeCategory $ageCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryData = [];
        $ageCategoryData['website_id'] = $ageCategory->website_id;
        $ageCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryData['subject_id'] = $ageCategory->id;
        $ageCategoryData['subject_type'] = get_class($ageCategory);
        $ageCategoryData['causer_id'] = $userObj->id;
        $ageCategoryData['causer_type'] = get_class($userObj);
        $ageCategoryData['description'] = $userObj->name.' '.'updated a age category.';
        $ageCategoryData['page'] = 'Teams';
        $ageCategoryData['section'] = 'Age categories';
        $ageCategoryData['action'] = 'updated';

        $this->saveActivityLog($ageCategoryData);
    }

    /**
     * Listen to the AgeCategory deleted event.
     *
     * @return void
     */
    public function deleted(AgeCategory $ageCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryData = [];
        $ageCategoryData['website_id'] = $ageCategory->website_id;
        $ageCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryData['subject_id'] = $ageCategory->id;
        $ageCategoryData['subject_type'] = get_class($ageCategory);
        $ageCategoryData['causer_id'] = $userObj->id;
        $ageCategoryData['causer_type'] = get_class($userObj);
        $ageCategoryData['description'] = $userObj->name.' '.'deleted a age category.';
        $ageCategoryData['page'] = 'Teams';
        $ageCategoryData['section'] = 'Age categories';
        $ageCategoryData['action'] = 'deleted';

        $this->saveActivityLog($ageCategoryData);
    }
}
