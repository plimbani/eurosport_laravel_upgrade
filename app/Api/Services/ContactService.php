<?php

namespace Laraspace\Api\Services;

use Landlord;
use Laraspace\Custom\Helper\Common;
use Laraspace\Api\Contracts\ContactContract;
use Laraspace\Api\Repositories\ContactRepository;

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
    $currentWebsite = Landlord::getTenants()['website'];
    $recipient = config('wot.inquiries_recipient');

    $email_msg = 'Message from ' . $currentWebsite->tournament_name  .'Website';
    $email_from = $data->email;
    $email_templates = 'emails.frontend.send_inquiries';

    Common::sendMail($email_details, $recipient, $email_msg, $email_templates, $email_from);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }
}
