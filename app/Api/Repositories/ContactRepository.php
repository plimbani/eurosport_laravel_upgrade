<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Contact;

class ContactRepository
{

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
    $contact->save();
  }
}
