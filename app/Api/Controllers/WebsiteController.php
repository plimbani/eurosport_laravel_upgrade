<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to define only contracts
use Laraspace\Api\Contracts\WebsiteContract;

/**
 * Website Description.
 *
 * @Resource("websites")
 *
 */
class WebsiteController extends BaseController
{
	/**
   * @var WebsiteContract
   */
  protected $websiteContract;

  /**
   * Create a new controller instance.
   *
   * @param WebsiteContract $websiteContract
   */
  public function __construct(WebsiteContract $websiteContract)
  {
  	$this->websiteContract = $websiteContract;
  }

    /**
     * Show all website details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/website")
     * @Versions({"v1"})
     * @Response(200, body={})
     */
    public function index()
    {
        return $this->websiteContract->index();
    }

  /**
   * Get all user websites
   *
   * Get a JSON representation of all the user websites.
   *
   * @Get("/getUserAccessibleWebsites")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getUserAccessibleWebsites()
  {
  	return $this->websiteContract->getUserAccessibleWebsites();
  }

  /*
   * Save website data
   *
   * @return response
   */
  public function saveWebsiteData(Request $request)
  {
    return $this->websiteContract->saveWebsiteData($request->all());
  }

  /*
   * Get website details
   *
   * @return response
   */
  public function websiteSummary(Request $request)
  {
    return $this->websiteContract->websiteSummary($request);
  }

  /*
   * Get customisation option
   *
   * @return response
   */
  public function getWebsiteCustomisationOptions(Request $request) {
    return $this->websiteContract->getWebsiteCustomisationOptions();
  }

  /*
   * Get image path
   *
   * @return response
   */
  public function getImagePath(Request $request) {
    return $this->websiteContract->getImagePath();
  }

  /*
   * Get website default pages
   *
   * @return response
   */
  public function getWebsiteDefaultPages(Request $request) {
    return $this->websiteContract->getWebsiteDefaultPages();
  }

  /**
   * Get all sponsors
   *
   * Get a JSON representation of all the sponsors
   *
   * @Get("/getSponsors")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getSponsors(Request $request, $websiteId)
  {
    return $this->websiteContract->getSponsors($websiteId);
  }

  /*
   * Upload website tournament logo
   *
   * @return response
   */
  public function uploadTournamentLogo(Request $request) {
    return $this->websiteContract->uploadTournamentLogo($request);
  }

  /*
   * Upload website social graphic
   *
   * @return response
   */
  public function uploadSocialGraphic(Request $request) {
    return $this->websiteContract->uploadSocialGraphic($request);
  }

  /*
   * Upload website sponsor upload image
   *
   * @return response
   */
  public function uploadSponsorImage(Request $request) {
    return $this->websiteContract->uploadSponsorImage($request);
  }
}
