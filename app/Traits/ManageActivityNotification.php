<?php

namespace Laraspace\Traits;

use Laraspace\Models\ActivityNotification;

trait ManageActivityNotification
{
    /*
       * Get notification id.
       *
       * @return response
       */
    protected function getNotificationId($userObj)
    {
        if ($userObj->roles->first()->slug == 'tournament.administrator') {
            $activityNotification = ActivityNotification::where('user_id', $userObj->id)->where('is_mail_sent', 0)->first();

            if ($activityNotification) {
                return $activityNotification->id;
            } else {
                $activityNotification = new ActivityNotification();
                $activityNotification->user_id = $userObj->id;
                $activityNotification->is_mail_sent = 0;
                $activityNotification->save();

                return $activityNotification->id;
            }
        }

        return 0;
    }
}
