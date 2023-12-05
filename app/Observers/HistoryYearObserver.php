<?php

namespace App\Observers;

use App\Models\HistoryYear;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class HistoryYearObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the HistoryYear created event.
     *
     * @return void
     */
    public function created(HistoryYear $historyYear)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyYearData = [];
        $historyYearData['website_id'] = $historyYear->website_id;
        $historyYearData['notification_id'] = $this->getNotificationId($userObj);
        $historyYearData['subject_id'] = $historyYear->id;
        $historyYearData['subject_type'] = get_class($historyYear);
        $historyYearData['causer_id'] = $userObj->id;
        $historyYearData['causer_type'] = get_class($userObj);
        $historyYearData['description'] = $userObj->name.' '.'added a new history year.';
        $historyYearData['page'] = 'Tournament';
        $historyYearData['section'] = 'History';
        $historyYearData['action'] = 'created';

        $this->saveActivityLog($historyYearData);
    }

    /**
     * Listen to the HistoryYear updated event.
     *
     * @return void
     */
    public function updated(HistoryYear $historyYear)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyYearData = [];
        $historyYearData['website_id'] = $historyYear->website_id;
        $historyYearData['notification_id'] = $this->getNotificationId($userObj);
        $historyYearData['subject_id'] = $historyYear->id;
        $historyYearData['subject_type'] = get_class($historyYear);
        $historyYearData['causer_id'] = $userObj->id;
        $historyYearData['causer_type'] = get_class($userObj);
        $historyYearData['description'] = $userObj->name.' '.'updated a history year.';
        $historyYearData['page'] = 'Tournament';
        $historyYearData['section'] = 'History';
        $historyYearData['action'] = 'updated';

        $this->saveActivityLog($historyYearData);
    }

    /**
     * Listen to the HistoryYear deleted event.
     *
     * @return void
     */
    public function deleted(HistoryYear $historyYear)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $historyYearData = [];
        $historyYearData['website_id'] = $historyYear->website_id;
        $historyYearData['notification_id'] = $this->getNotificationId($userObj);
        $historyYearData['subject_id'] = $historyYear->id;
        $historyYearData['subject_type'] = get_class($historyYear);
        $historyYearData['causer_id'] = $userObj->id;
        $historyYearData['causer_type'] = get_class($userObj);
        $historyYearData['description'] = $userObj->name.' '.'deleted a history year.';
        $historyYearData['page'] = 'Tournament';
        $historyYearData['section'] = 'History';
        $historyYearData['action'] = 'deleted';

        $this->saveActivityLog($historyYearData);
    }
}
