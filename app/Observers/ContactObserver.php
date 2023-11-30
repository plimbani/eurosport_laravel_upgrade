<?php

namespace App\Observers;

use App\Models\Contact;
use App\Traits\AuthUserDetail;
use App\Traits\ManageActivityLog;
use App\Traits\ManageActivityNotification;

class ContactObserver
{
    use ManageActivityLog, AuthUserDetail, ManageActivityNotification;

    /**
     * Listen to the Contact created event.
     *
     * @return void
     */
    public function created(Contact $contact)
    {
    }

    /**
     * Listen to the Contact updated event.
     *
     * @return void
     */
    public function updated(Contact $contact)
    {
        $userObj = $this->getCurrentLoggedInUserDetail();

        $contactData = [];
        $contactData['website_id'] = $contact->website_id;
        $contactData['notification_id'] = $this->getNotificationId($userObj);
        $contactData['subject_id'] = $contact->id;
        $contactData['subject_type'] = get_class($contact);
        $contactData['causer_id'] = $userObj->id;
        $contactData['causer_type'] = get_class($userObj);
        $contactData['description'] = $userObj->name.' '.'updated a contact page.';
        $contactData['page'] = 'Contact';
        $contactData['section'] = 'Content';
        $contactData['action'] = 'updated';

        $this->saveActivityLog($contactData);
    }

    /**
     * Listen to the Contact deleted event.
     *
     * @return void
     */
    public function deleted(Contact $contact)
    {

    }
}
