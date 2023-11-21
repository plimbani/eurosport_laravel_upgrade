<?php

namespace Laraspace\Observers;

use Laraspace\Models\AgeCategoryTeam;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class AgeCategoryTeamObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the AgeCategoryTeam created event.
     *
     * @return void
     */
    public function created(AgeCategoryTeam $ageCategoryTeam)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryTeamData = [];
        $ageCategoryTeamData['website_id'] = $ageCategoryTeam->website_id;
        $ageCategoryTeamData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryTeamData['subject_id'] = $ageCategoryTeam->id;
        $ageCategoryTeamData['subject_type'] = get_class($ageCategoryTeam);
        $ageCategoryTeamData['causer_id'] = $userObj->id;
        $ageCategoryTeamData['causer_type'] = get_class($userObj);
        $ageCategoryTeamData['description'] = $userObj->name.' '.'added a new age category team.';
        $ageCategoryTeamData['page'] = 'Teams';
        $ageCategoryTeamData['section'] = 'Age categories';
        $ageCategoryTeamData['action'] = 'created';

        $this->saveActivityLog($ageCategoryTeamData);
    }

    /**
     * Listen to the AgeCategoryTeam updated event.
     *
     * @return void
     */
    public function updated(AgeCategoryTeam $ageCategoryTeam)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryTeamData = [];
        $ageCategoryTeamData['website_id'] = $ageCategoryTeam->website_id;
        $ageCategoryTeamData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryTeamData['subject_id'] = $ageCategoryTeam->id;
        $ageCategoryTeamData['subject_type'] = get_class($ageCategoryTeam);
        $ageCategoryTeamData['causer_id'] = $userObj->id;
        $ageCategoryTeamData['causer_type'] = get_class($userObj);
        $ageCategoryTeamData['description'] = $userObj->name.' '.'updated a age category team.';
        $ageCategoryTeamData['page'] = 'Teams';
        $ageCategoryTeamData['section'] = 'Age categories';
        $ageCategoryTeamData['action'] = 'updated';

        $this->saveActivityLog($ageCategoryTeamData);
    }

    /**
     * Listen to the AgeCategoryTeam deleted event.
     *
     * @return void
     */
    public function deleted(AgeCategoryTeam $ageCategoryTeam)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $ageCategoryTeamData = [];
        $ageCategoryTeamData['website_id'] = $ageCategoryTeam->website_id;
        $ageCategoryTeamData['notification_id'] = $this->getNotificationId($userObj);
        $ageCategoryTeamData['subject_id'] = $ageCategoryTeam->id;
        $ageCategoryTeamData['subject_type'] = get_class($ageCategoryTeam);
        $ageCategoryTeamData['causer_id'] = $userObj->id;
        $ageCategoryTeamData['causer_type'] = get_class($userObj);
        $ageCategoryTeamData['description'] = $userObj->name.' '.'deleted a age category team.';
        $ageCategoryTeamData['page'] = 'Teams';
        $ageCategoryTeamData['section'] = 'Age categories';
        $ageCategoryTeamData['action'] = 'deleted';

        $this->saveActivityLog($ageCategoryTeamData);
    }
}
