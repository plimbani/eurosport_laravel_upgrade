<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Laraspace\Models\Page;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\ProgramContract;

class ProgramController extends Controller
{
    /**
     * @var ProgramContract
     */
    protected $programContract;

    /**
     * @var Home page name
     */
    protected $programPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProgramContract $programContract, PageService $pageService)
    {
      $this->pageService = $pageService;
      $this->programContract = $programContract;
      $this->programPageName = 'program';
    }

    /**
     * Get program page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProgramPageDetails(Request $request)
    {
      $varsForView = [];
      $websiteId = Landlord::getTenants()['website']->id;
      $pageDetail = $this->pageService->getPageDetails($this->programPageName, $websiteId);

      // Page title
      $varsForView['pageTitle'] = $pageDetail->title;

      return view('frontend.program', $varsForView);
    }

    /**
     * Get program additional page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdditionalProgramPageDetails(Request $request, $domain, $additionalPageName)
    {
      $varsForView = [];

      $websiteId = Landlord::getTenants()['website']->id;
      $parentPageDetail = $this->pageService->getPageDetails($this->programPageName, $websiteId);
      $page = Page::where('parent_id', $parentPageDetail->id)
                    ->where('website_id', $websiteId)
                    ->where('page_name', $additionalPageName)
                    ->first();

      // page title
      $varsForView['pageTitle'] = $parentPageDetail->title . ' - ' . $page->title;

      return view('frontend.program', $varsForView);
    }
}
