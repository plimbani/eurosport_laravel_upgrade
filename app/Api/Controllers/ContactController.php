<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\ContactContract;

use Laraspace\Http\Requests\Contact\GetContactDataRequest;
use Laraspace\Http\Requests\Contact\StoreUpdateRequest;

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
  public function getContactDetails(GetContactDataRequest $request, $websiteId)
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
  public function saveContactDetails(StoreUpdateRequest $request)
  {
    return $this->contactContract->saveContactDetails($request);
  }
}
