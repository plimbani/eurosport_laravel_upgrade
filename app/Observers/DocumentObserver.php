<?php

namespace App\Observers;

use App\Models\Document;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class DocumentObserver
{
    use AuthUserDetail, ManageActivityLog, ManageActivityNotification;

    /**
     * Listen to the Document created event.
     *
     * @return void
     */
    public function created(Document $document)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $documentData = [];
        $documentData['website_id'] = $document->website_id;
        $documentData['notification_id'] = $this->getNotificationId($userObj);
        $documentData['subject_id'] = $document->id;
        $documentData['subject_type'] = get_class($document);
        $documentData['causer_id'] = $userObj->id;
        $documentData['causer_type'] = get_class($userObj);
        $documentData['description'] = $userObj->name.' '.'added a new document.';
        $documentData['page'] = 'Media';
        $documentData['section'] = 'Files and documents';
        $documentData['action'] = 'created';

        $this->saveActivityLog($documentData);
    }

    /**
     * Listen to the Document updated event.
     *
     * @return void
     */
    public function updated(Document $document)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $documentData = [];
        $documentData['website_id'] = $document->website_id;
        $documentData['notification_id'] = $this->getNotificationId($userObj);
        $documentData['subject_id'] = $document->id;
        $documentData['subject_type'] = get_class($document);
        $documentData['causer_id'] = $userObj->id;
        $documentData['causer_type'] = get_class($userObj);
        $documentData['description'] = $userObj->name.' '.'updated a document.';
        $documentData['page'] = 'Media';
        $documentData['section'] = 'Files and documents';
        $documentData['action'] = 'updated';

        $this->saveActivityLog($documentData);
    }

    /**
     * Listen to the Document deleted event.
     *
     * @return void
     */
    public function deleted(Document $document)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $documentData = [];
        $documentData['website_id'] = $document->website_id;
        $documentData['notification_id'] = $this->getNotificationId($userObj);
        $documentData['subject_id'] = $document->id;
        $documentData['subject_type'] = get_class($document);
        $documentData['causer_id'] = $userObj->id;
        $documentData['causer_type'] = get_class($userObj);
        $documentData['description'] = $userObj->name.' '.'deleted a document.';
        $documentData['page'] = 'Media';
        $documentData['section'] = 'Files and documents';
        $documentData['action'] = 'deleted';

        $this->saveActivityLog($documentData);
    }
}
