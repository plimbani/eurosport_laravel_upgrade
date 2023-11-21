<?php

namespace Laraspace\Observers;

use Laraspace\Models\HistoryAgeCategory;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class HistoryAgeCategoryObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the HistoryAgeCategory created event.
     *
     * @return void
     */
    public function created(HistoryAgeCategory $historyAgeCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyAgeCategoryData = [];
        $historyAgeCategoryData['website_id'] = $historyAgeCategory->website_id;
        $historyAgeCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $historyAgeCategoryData['subject_id'] = $historyAgeCategory->id;
        $historyAgeCategoryData['subject_type'] = get_class($historyAgeCategory);
        $historyAgeCategoryData['causer_id'] = $userObj->id;
        $historyAgeCategoryData['causer_type'] = get_class($userObj);
        $historyAgeCategoryData['description'] = $userObj->name.' '.'added a new history age category.';
        $historyAgeCategoryData['page'] = 'Tournament';
        $historyAgeCategoryData['section'] = 'History';
        $historyAgeCategoryData['action'] = 'created';

        $this->saveActivityLog($historyAgeCategoryData);
    }

    /**
     * Listen to the HistoryAgeCategory updated event.
     *
     * @return void
     */
    public function updated(HistoryAgeCategory $historyAgeCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyAgeCategoryData = [];
        $historyAgeCategoryData['website_id'] = $historyAgeCategory->website_id;
        $historyAgeCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $historyAgeCategoryData['subject_id'] = $historyAgeCategory->id;
        $historyAgeCategoryData['subject_type'] = get_class($historyAgeCategory);
        $historyAgeCategoryData['causer_id'] = $userObj->id;
        $historyAgeCategoryData['causer_type'] = get_class($userObj);
        $historyAgeCategoryData['description'] = $userObj->name.' '.'updated a history age category.';
        $historyAgeCategoryData['page'] = 'Tournament';
        $historyAgeCategoryData['section'] = 'History';
        $historyAgeCategoryData['action'] = 'updated';

        $this->saveActivityLog($historyAgeCategoryData);
    }

    /**
     * Listen to the HistoryAgeCategory deleted event.
     *
     * @return void
     */
    public function deleted(HistoryAgeCategory $historyAgeCategory)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyAgeCategoryData = [];
        $historyAgeCategoryData['website_id'] = $historyAgeCategory->website_id;
        $historyAgeCategoryData['notification_id'] = $this->getNotificationId($userObj);
        $historyAgeCategoryData['subject_id'] = $historyAgeCategory->id;
        $historyAgeCategoryData['subject_type'] = get_class($historyAgeCategory);
        $historyAgeCategoryData['causer_id'] = $userObj->id;
        $historyAgeCategoryData['causer_type'] = get_class($userObj);
        $historyAgeCategoryData['description'] = $userObj->name.' '.'deleted a history age category.';
        $historyAgeCategoryData['page'] = 'Tournament';
        $historyAgeCategoryData['section'] = 'History';
        $historyAgeCategoryData['action'] = 'deleted';

        $this->saveActivityLog($historyAgeCategoryData);
    }
}
