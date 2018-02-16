<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\WebsiteVenueContract;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 * @Author Knayak@aecordigital.com
 */
class WebsiteVenueController extends BaseController
{
  /**
   * @var stayContract
   */
  protected $websiteVenueContract;

  /**
   * @param object $tournamentObj
   */
  public function __construct(WebsiteVenueContract $websiteVenueContract)
  {
      $this->websiteVenueContract = $websiteVenueContract;
  }

  /*
   * Save venue page data
   *
   * @return response
   */ 
  public function saveVenuePageData(Request $request)
  {
    return $this->websiteVenueContract->saveVenuePageData($request->all());
  }

    /**
     * Show all Tournament Details.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/tournament")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function index()
    {
        return $this->venueObj->index();
    }

    

    /**
     * Show json Data for Template.
     *
     * Get a JSON representation of all the Age Groups.
     *
     * @Get("/templates")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "json": "foo"})
     */
    public function getVenues($tournamentId)
    {
        return $this->venueObj->index($tournamentId);
    }
    
    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/tournament/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function create(Request $request)
    {                
        return $this->venueObj->create($request);
    }

    /**
     * Edit  Torunament.
     *
     * @Post("/tournament/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->venueObj->edit($request);
    }

    /**
     * Delete  Torunament.
     *
     * @Post("/tournament/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(Request $request)
    {
        return $this->venueObj->delete($request);
    }
}
