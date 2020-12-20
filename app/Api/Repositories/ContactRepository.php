<?php

namespace Laraspace\Api\Repositories;

use DB;
use Landlord;
use Carbon\Carbon;
use Laraspace\Models\Contact;
use Laraspace\Models\Inquiry;
use Laraspace\Traits\AuthUserDetail;

class ContactRepository
{
  use AuthUserDetail;

  /**
   * Create a new controller instance.
   */
  public function __construct()
  {
  }

  /*
   * Get contact details
   *
   * @return response
   */
  public function getContactDetails($websiteId)
  {
    $contact = Contact::where('website_id', $websiteId)->first();
    return $contact;
  }

  /*
   * Save contact details
   *
   * @return response
   */
  public function saveContactDetails($data)
  {
    $contact = Contact::where('website_id', $data['website_id'])->first();
    isset($data['contact_name']) && trim($data['contact_name'])!='' ? $contact->contact_name = $data['contact_name'] : null;
    isset($data['phone_number']) && trim($data['phone_number'])!='' ? $contact->phone_number = $data['phone_number'] : null;
    isset($data['email_address']) && trim($data['email_address'])!='' ? $contact->email_address = $data['email_address'] : null;
    isset($data['address']) && trim($data['address'])!='' ? $contact->address = $data['address'] : null;
    $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
    if($contact->isDirty()) {
      $contact->updated_by = $currentLoggedInUserId;
      $contact->save();
    }
  }

  /*
   * Save inquiry details
   *
   * @return response
   */
  public function saveInquiryDetails($data)
  {
    $ipAddress = $data->ip();
    $data = $data->all();
    $websiteId = Landlord::getTenants()['website']->id;

    $inquiry = new Inquiry();
    $inquiry->name = $data['name'];
    $inquiry->email = $data['email'];
    $inquiry->telephone_number = $data['telephone_number'];
    $inquiry->subject = $data['subject'];
    $inquiry->message = $data['message'];
    $inquiry->website_id = $websiteId;
    $inquiry->ip_address = $ipAddress;
    $inquiry->created_at = Carbon::now();
    $inquiry->save();

    return $inquiry;
  }
}
