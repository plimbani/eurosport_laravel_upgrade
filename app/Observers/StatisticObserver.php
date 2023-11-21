<?php

namespace Laraspace\Observers;

use Laraspace\Models\Statistic;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class StatisticObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Statistic created event.
     *
     * @return void
     */
    public function created(Statistic $statistic)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $statisticData = [];
        $statisticData['website_id'] = $statistic->website_id;
        $statisticData['notification_id'] = $this->getNotificationId($userObj);
        $statisticData['subject_id'] = $statistic->id;
        $statisticData['subject_type'] = get_class($statistic);
        $statisticData['causer_id'] = $userObj->id;
        $statisticData['causer_type'] = get_class($userObj);
        $statisticData['description'] = $userObj->name.' '.'added a new statistic.';
        $statisticData['page'] = 'Homepage';
        $statisticData['section'] = 'Statistics';
        $statisticData['action'] = 'created';

        $this->saveActivityLog($statisticData);
    }

    /**
     * Listen to the Statistic updated event.
     *
     * @param  \Laraspace\Models\Statistic  $Statistic
     * @return void
     */
    public function updated(Statistic $statistic)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $statisticData = [];
        $statisticData['website_id'] = $statistic->website_id;
        $statisticData['notification_id'] = $this->getNotificationId($userObj);
        $statisticData['subject_id'] = $statistic->id;
        $statisticData['subject_type'] = get_class($statistic);
        $statisticData['causer_id'] = $userObj->id;
        $statisticData['causer_type'] = get_class($userObj);
        $statisticData['description'] = $userObj->name.' '.'updated a statistic.';
        $statisticData['page'] = 'Homepage';
        $statisticData['section'] = 'Statistics';
        $statisticData['action'] = 'updated';

        $this->saveActivityLog($statisticData);
    }

    /**
     * Listen to the Statistic deleted event.
     *
     * @param  \Laraspace\Models\Statistic  $Statistic
     * @return void
     */
    public function deleted(Statistic $statistic)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $statisticData = [];
        $statisticData['website_id'] = $statistic->website_id;
        $statisticData['notification_id'] = $this->getNotificationId($userObj);
        $statisticData['subject_id'] = $statistic->id;
        $statisticData['subject_type'] = get_class($statistic);
        $statisticData['causer_id'] = $userObj->id;
        $statisticData['causer_type'] = get_class($userObj);
        $statisticData['description'] = $userObj->name.' '.'deleted a new statistic.';
        $statisticData['page'] = 'Homepage';
        $statisticData['section'] = 'Statistics';
        $statisticData['action'] = 'deleted';

        $this->saveActivityLog($statisticData);
    }
}
