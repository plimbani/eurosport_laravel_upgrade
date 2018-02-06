<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\HomeContract;

/**
 * Home description.
 *
 * @Resource("pages")
 *
 */
class HomeController extends BaseController
{
	/**
   * @var HomeContract
   */
  protected $homeContract;

  /**
   * Create a new controller instance.
   *
   * @param HomeContract $homeContract
   */
  public function __construct(HomeContract $homeContract)
  {
  	$this->homeContract = $homeContract;
  }
}
