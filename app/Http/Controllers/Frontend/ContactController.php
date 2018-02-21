<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\ContactContract;

class ContactController extends Controller
{
    /**
     * @var ContactContract
     */
    protected $contactContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ContactContract $contactContract)
    {
        $this->contactContract = $contactContract;
    }

    /**
     * Get contact page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContactPageDetails(Request $request)
    {
        $varsForView = [];

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
