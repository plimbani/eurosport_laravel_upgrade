<?php

namespace Laraspace\Api\Services;

use Mail;
use Landlord;
use Laraspace\Mail\SendMail;
use Laraspace\Api\Contracts\ContactContract;
use Laraspace\Api\Repositories\ContactRepository;
use Laraspace\Models\Website;

class ContactService implements ContactContract
{
  /**
   * @var ContactRepository
   */
  protected $contactRepo;

	/**
   *  Success message
   */
  const SUCCESS_MSG = 'Data Sucessfully inserted';

  /**
   *  Error message
   */
  const ERROR_MSG = 'Error in Data';

  /**
   * Create a new controller instance.
   *
   * @param ContactRepository $contactRepo
   */
  public function __construct(ContactRepository $contactRepo)
  {
    $this->contactRepo = $contactRepo;
  }

  /*
   * Get contact details
   *
   * @return response
   */
  public function getContactDetails($websiteId)
  {
    $data = $this->contactRepo->getContactDetails($websiteId);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save contact details
   *
   * @return response
   */
  public function saveContactDetails($data)
  {
    $data = $this->contactRepo->saveContactDetails($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save inquiry details
   *
   * @return response
   */
  public function saveInquiryDetails($data)
  {
    $data = $this->contactRepo->saveInquiryDetails($data);

    $email_details = $data;
    $currentWebsite = Landlord::getTenants()->count() && Landlord::getTenants()['website'];
    if (!$currentWebsite && $data['website_id']) {
      $currentWebsite = Website::where('id', $data['website_id'])->first();
    }
    
    $recipient = config('wot.inquiries_recipient');

    $subject = 'Message from ' . $currentWebsite->tournament_name  .' Website';
    $email_templates = 'emails.frontend.send_inquiries';

    Mail::to($recipient['to'])
          ->bcc($recipient['bcc'])
          ->send(new SendMail($email_details, $subject, $email_templates, NULL, $data['email'], $data['name']));

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
