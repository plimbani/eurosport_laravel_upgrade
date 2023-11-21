<?php

namespace Laraspace\Traits;

use Laraspace\Models\ActivityFeed;

trait ManageActivityLog
{
    /*
      * Save activity log
      *
      * @return response
      */
    protected function saveActivityLog($data)
    {
        $activityFeed = new ActivityFeed();
        $activityFeed->website_id = $data['website_id'];
        $activityFeed->notification_id = $data['notification_id'];
        $activityFeed->subject_id = $data['subject_id'];
        $activityFeed->subject_type = $data['subject_type'];
        $activityFeed->causer_id = $data['causer_id'];
        $activityFeed->causer_type = $data['causer_type'];
        $activityFeed->description = $data['description'];
        $activityFeed->page = $data['page'];
        $activityFeed->section = $data['section'];
        $activityFeed->action = $data['action'];
        $activityFeed->save();
    }
}
