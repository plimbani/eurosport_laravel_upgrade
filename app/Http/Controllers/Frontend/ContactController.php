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
     * @var Contact page name
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

        $contactPageDetail = $this->contactContract->getContactDetails($websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;
        $varsForView['contactPageDetail'] = $contactPageDetail['data'];

        return view('frontend.contact', $varsForView);
    }

    /**
     * Submit enquiry
     *
     * @return \Illuminate\Http\Response
     */
    public function submitInquiry(Request $request)
    {
        $inquiryDetail = $this->contactContract->saveInquiryDetails($request);

        return response()->json([
            'status_code' => '200',
            'status' => 'success',
            'message' => 'Inquiry has been submitted'
        ]);
    }

}
