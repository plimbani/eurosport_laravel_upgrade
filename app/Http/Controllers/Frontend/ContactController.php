<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\ContactContract;

class ContactController extends Controller
{
    /**
     * @var ContactContract
     */
    protected $contactContract;

    /**
     * @var Home page name
     */
    protected $contactPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ContactContract $contactContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->contactContract = $contactContract;
        $this->contactPageName = 'contact';
    }

    /**
     * Get contact page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContactPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $pageDetail = $this->pageService->getPageDetails($this->contactPageName, $websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;

        return view('frontend.contact', $varsForView);
    }

    /**
     * Submit enquiry
     *
     * @return \Illuminate\Http\Response
     */
    public function submitEnquiry(Request $request)
    {
        $varsForView = [];

        return view('frontend.contact', $varsForView);
    }
}
