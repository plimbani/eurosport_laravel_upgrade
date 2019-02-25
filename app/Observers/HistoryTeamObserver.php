<?php

namespace Laraspace\Observers;

use Laraspace\Models\HistoryTeam;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class HistoryTeamObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the HistoryTeam created event.
     *
     * @param  \Laraspace\Models\HistoryTeam $historyTeam
     * @return void
     */
    public function created(HistoryTeam $historyTeam)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $historyTeamData = [];
      $historyTeamData['website_id'] = $historyTeam->website_id;
      $historyTeamData['notification_id'] = $this->getNotificationId($userObj);
      $historyTeamData['subject_id'] = $historyTeam->id;
      $historyTeamData['subject_type'] = get_class($historyTeam);
      $historyTeamData['causer_id'] = $userObj->id;
      $historyTeamData['causer_type'] = get_class($userObj);
      $historyTeamData['description'] = $userObj->name .' '. 'added a new history team.';
      $historyTeamData['page'] = 'Tournament';
      $historyTeamData['section'] = 'History';
      $historyTeamData['action'] = 'created';

      $this->saveActivityLog($historyTeamData);
    }

    /**
     * Listen to the HistoryTeam updated event.
     *
     * @param  \Laraspace\Models\HistoryTeam $historyTeam
     * @return void
     */
    public function updated(HistoryTeam $historyTeam)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $historyTeamData = [];
      $historyTeamData['website_id'] = $historyTeam->website_id;
      $historyTeamData['notification_id'] = $this->getNotificationId($userObj);
      $historyTeamData['subject_id'] = $historyTeam->id;
      $historyTeamData['subject_type'] = get_class($historyTeam);
      $historyTeamData['causer_id'] = $userObj->id;
      $historyTeamData['causer_type'] = get_class($userObj);
      $historyTeamData['description'] = $userObj->name .' '. 'updated a history team.';
      $historyTeamData['page'] = 'Tournament';
      $historyTeamData['section'] = 'History';
      $historyTeamData['action'] = 'updated';

      $this->saveActivityLog($historyTeamData);
    }

    /**
     * Listen to the HistoryTeam deleted event.
     *
     * @param  \Laraspace\Models\HistoryTeam $historyTeam
     * @return void
     */
    public function deleted(HistoryTeam $historyTeam)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $historyTeamData = [];
      $historyTeamData['website_id'] = $historyTeam->website_id;
      $historyTeamData['notification_id'] = $this->getNotificationId($userObj);
      $historyTeamData['subject_id'] = $historyTeam->id;
      $historyTeamData['subject_type'] = get_class($historyTeam);
      $historyTeamData['causer_id'] = $userObj->id;
      $historyTeamData['causer_type'] = get_class($userObj);
      $historyTeamData['description'] = $userObj->name .' '. 'deleted a history team.';
      $historyTeamData['page'] = 'Tournament';
      $historyTeamData['section'] = 'History';
      $historyTeamData['action'] = 'deleted';

      $this->saveActivityLog($historyTeamData);
    }
}