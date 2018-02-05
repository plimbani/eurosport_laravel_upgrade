<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Website;

class WebsiteRepository
{
	/**
   * Create a new controller instance.
   */
	public function __construct()
  {
    
  }

  public function getAll($status='', $user=null)
  {
    $websites = Website::All();
    return $websites;
  }

  /*
   * Get user accessible websites
   *
   * @param User $user
   *
   * @return response
   */
  public function getUserAccessibleWebsites($user)
  {
  	$websites = Website::All();

  	if($user) {
      $websites = $user->websites();
    }

    return $websites;
  }
}
