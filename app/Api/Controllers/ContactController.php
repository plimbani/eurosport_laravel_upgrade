<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\ContactContract;

/**
 * Contact description.
 *
 */
class ContactController extends BaseController
{
	/**
   * @var ContactContract
   */
  protected $contactContract;

  /**
   * Create a new controller instance.
   *
   * @param ContactContract $contactContract
   */
  public function __construct(ContactContract $contactContract)
  {
  	$this->contactContract = $contactContract;
  }

  /**
   * Get contact details
   *
   *
   * @Get("/getContactDetails")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getContactDetails(Request $request, $websiteId)
  {
    return $this->contactContract->getContactDetails($websiteId);
  }

  /**
   * Save contact details
   *
   *
   * @Get("/saveContactDetails")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function saveContactDetails(Request $request)
  {
    return $this->contactContract->saveContactDetails($request);
  }
}
