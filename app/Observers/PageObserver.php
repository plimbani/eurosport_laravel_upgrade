<?php

namespace Laraspace\Observers;

use Laraspace\Models\Page;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Traits\ManageActivityLog;
use Laraspace\Traits\ManageActivityNotification;

class PageObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Page created event.
     *
     * @param  \Laraspace\Models\Page $page
     * @return void
     */
    public function created(Page $page)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $pageData = [];
      $pageData['website_id'] = $page->website_id;
      $pageData['notification_id'] = $this->getNotificationId($userObj);
      $pageData['subject_id'] = $page->id;
      $pageData['subject_type'] = get_class($page);
      $pageData['causer_id'] = $userObj->id;
      $pageData['causer_type'] = get_class($userObj);
      $pageData['description'] = $userObj->name .' '. 'added a new page.';
      $pageData['page'] = 'Homepage';
      $pageData['section'] = 'Organisers';
      $pageData['action'] = 'created';

      $this->saveActivityLog($pageData);
    }

    /**
     * Listen to the Page updated event.
     *
     * @param  \Laraspace\Models\Page $Page
     * @return void
     */
    public function updated(Page $page)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $pageData = [];
      $pageData['website_id'] = $page->website_id;
      $pageData['notification_id'] = $this->getNotificationId($userObj);
      $pageData['subject_id'] = $page->id;
      $pageData['subject_type'] = get_class($page);
      $pageData['causer_id'] = $userObj->id;
      $pageData['causer_type'] = get_class($userObj);
      $pageData['description'] = $userObj->name .' '. 'updated a page.';
      $pageData['page'] = 'Homepage';
      $pageData['section'] = 'Organisers';
      $pageData['action'] = 'updated';

      $this->saveActivityLog($pageData);
    }

    /**
     * Listen to the Page deleted event.
     *
     * @param  \Laraspace\Models\Page $Page
     * @return void
     */
    public function deleted(Page $page)
    {
      $userObj = $this->getCurrentLoggedInUserDetail();

      $pageData = [];
      $pageData['website_id'] = $page->website_id;
      $pageData['notification_id'] = $this->getNotificationId($userObj);
      $pageData['subject_id'] = $page->id;
      $pageData['subject_type'] = get_class($page);
      $pageData['causer_id'] = $userObj->id;
      $pageData['causer_type'] = get_class($userObj);
      $pageData['description'] = $userObj->name .' '. 'deleted a page.';
      $pageData['page'] = 'Homepage';
      $pageData['section'] = 'Organisers';
      $pageData['action'] = 'deleted';

      $this->saveActivityLog($pageData);
    }
}