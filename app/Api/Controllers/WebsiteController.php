<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Laraspace\Http\Requests\Website\SummaryRequest;
use Laraspace\Http\Requests\Website\GetSponsorsRequest;
use Laraspace\Http\Requests\Website\StoreUpdateRequest;
use Laraspace\Http\Requests\Website\GetAllWebsitesRequest;
use Laraspace\Http\Requests\Website\GetWebsiteDataRequest;
use Laraspace\Http\Requests\Website\UploadHeroImageRequest;
use Laraspace\Http\Requests\Website\UploadWelcomeImageRequest;
use Laraspace\Http\Requests\Website\UploadSponsorImageRequest;
use Laraspace\Http\Requests\Website\UploadOrganiserLogoRequest;
use Laraspace\Http\Requests\Website\UploadSocialGraphicRequest;
use Laraspace\Http\Requests\Website\UploadTournamentLogoRequest;
use Laraspace\Http\Requests\Website\GetWebsiteDefaultPagesRequest;
use Laraspace\Http\Requests\Website\GetWebsiteCustomisationRequest;
use Laraspace\Http\Requests\Website\GetUserAccessibleWebsitesRequest;
use Laraspace\Http\Requests\Website\GetWebsiteConfigurationDetailRequest;

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
    public function index(GetAllWebsitesRequest $request)
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
  public function getUserAccessibleWebsites(GetUserAccessibleWebsitesRequest $request)
  {
  	return $this->websiteContract->getUserAccessibleWebsites();
  }

  /*
   * Save website data
   *
   * @return response
   */
  public function saveWebsiteData(StoreUpdateRequest $request)
  {
    return $this->websiteContract->saveWebsiteData($request->all());
  }

  /*
   * Get website details
   *
   * @return response
   */
  public function websiteSummary(SummaryRequest $request)
  {
    return $this->websiteContract->websiteSummary($request);
  }

  /*
   * Get customisation option
   *
   * @return response
   */
  public function getWebsiteCustomisationOptions(GetWebsiteCustomisationRequest $request) {
    return $this->websiteContract->getWebsiteCustomisationOptions();
  }

  /*
   * Get image path
   *
   * @return response
   */
  public function getConfigurationDetail(GetWebsiteConfigurationDetailRequest $request) {
    return $this->websiteContract->getConfigurationDetail();
  }

  /*
   * Get website default pages
   *
   * @return response
   */
  public function getWebsiteDefaultPages(GetWebsiteDefaultPagesRequest $request) {
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
  public function getSponsors(GetSponsorsRequest $request, $websiteId)
  {
    return $this->websiteContract->getSponsors($websiteId);
  }

  /*
   * Upload website tournament logo
   *
   * @return response
   */
  public function uploadTournamentLogo(UploadTournamentLogoRequest $request) {
    return $this->websiteContract->uploadTournamentLogo($request);
  }

  /*
   * Upload website social graphic
   *
   * @return response
   */
  public function uploadSocialGraphic(UploadSocialGraphicRequest $request) {
    return $this->websiteContract->uploadSocialGraphic($request);
  }

  /*
   * Upload website sponsor upload image
   *
   * @return response
   */
  public function uploadSponsorImage(UploadSponsorImageRequest $request) {
    return $this->websiteContract->uploadSponsorImage($request);
  }

  /*
   * Upload website hero image
   *
   * @return response
   */
  public function uploadHeroImage(UploadHeroImageRequest $request) {
    return $this->websiteContract->uploadHeroImage($request);
  }

  /*
   * Upload welcome image
   *
   * @return response
   */
  public function uploadWelcomeImage(UploadWelcomeImageRequest $request) {
    return $this->websiteContract->uploadWelcomeImage($request);
  }

  /*
   * Upload organiser image
   *
   * @return response
   */
  public function uploadOrganiserLogo(UploadOrganiserLogoRequest $request) {
    return $this->websiteContract->uploadOrganiserLogo($request);
  }
  
  /**
   * Get website details
   *
   * Get a JSON representation of all the website
   *
   * @Get("/getWebsiteDetails")
   * @Versions({"v1"})
   * @Response(200, body={})
   */
  public function getWebsiteDetails(GetWebsiteDataRequest $request, $websiteId)
  {
    return $this->websiteContract->getWebsiteDetails($websiteId);
  }
}
