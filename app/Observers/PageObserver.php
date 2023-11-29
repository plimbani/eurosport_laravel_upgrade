<?php

namespace App\Observers;

use App\Models\Page;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;
use App\Traits\TrackActivitySection;

class PageObserver
{
    use ManageActivityLog, AuthUserDetail, TrackActivitySection, ManageActivityNotification;

    /**
     * Listen to the Page created event.
     *
     * @return void
     */
    public function created(Page $page)
    {
        if ($page->is_additional_page == 1) {
            $userObj = $this->getCurrentLoggedInUserDetail();
            $pageTitleAndSection = $this->getPageTitleAndSection($page);

            $pageData = [];
            $pageData['website_id'] = $page->website_id;
            $pageData['notification_id'] = $this->getNotificationId($userObj);
            $pageData['subject_id'] = $page->id;
            $pageData['subject_type'] = get_class($page);
            $pageData['causer_id'] = $userObj->id;
            $pageData['causer_type'] = get_class($userObj);
            $pageData['description'] = $userObj->name.' '.'added a '.$page->title.' page.';
            $pageData['page'] = $pageTitleAndSection['page_title'];
            $pageData['section'] = $pageTitleAndSection['section'];
            $pageData['action'] = 'updated';

            $this->saveActivityLog($pageData);
        }
    }

    /**
     * Listen to the Page updated event.
     *
     * @param  \App\Models\Page  $Page
     * @return void
     */
    public function updated(Page $page)
    {
        $dirtyFields = collect($page->getDirty())->filter(function ($value, $key) {
            // We don't care if timestamps are dirty, we're not tracking those
            return ! in_array($key, ['created_at', 'updated_at']);
        });

        if ($dirtyFields->count() == 0) {
            return;
        }

        $userObj = $this->getCurrentLoggedInUserDetail();
        $pageTitleAndSection = $this->getPageTitleAndSection($page);

        $pageData = [];
        $pageData['website_id'] = $page->website_id;
        $pageData['notification_id'] = $this->getNotificationId($userObj);
        $pageData['subject_id'] = $page->id;
        $pageData['subject_type'] = get_class($page);
        $pageData['causer_id'] = $userObj->id;
        $pageData['causer_type'] = get_class($userObj);
        $pageData['description'] = $userObj->name.' '.'updated a '.$pageTitleAndSection['page_title'].' page.';
        $pageData['page'] = $pageTitleAndSection['page_title'];
        $pageData['section'] = $pageTitleAndSection['section'];
        $pageData['action'] = 'updated';

        $this->saveActivityLog($pageData);
    }

    /**
     * Listen to the Page deleted event.
     *
     * @param  \App\Models\Page  $Page
     * @return void
     */
    public function deleted(Page $page)
    {
        if ($page->is_additional_page == 1) {
            $userObj = $this->getCurrentLoggedInUserDetail();
            $pageTitleAndSection = $this->getPageTitleAndSection($page);

            $pageData = [];
            $pageData['website_id'] = $page->website_id;
            $pageData['notification_id'] = $this->getNotificationId($userObj);
            $pageData['subject_id'] = $page->id;
            $pageData['subject_type'] = get_class($page);
            $pageData['causer_id'] = $userObj->id;
            $pageData['causer_type'] = get_class($userObj);
            $pageData['description'] = $userObj->name.' '.'deleted a '.$page->title.' page.';
            $pageData['page'] = $pageTitleAndSection['page_title'];
            $pageData['section'] = 'Additional page';
            $pageData['action'] = 'deleted';

            $this->saveActivityLog($pageData);
        }
    }
}
