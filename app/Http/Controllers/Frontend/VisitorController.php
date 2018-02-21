<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\VisitorContract;

class VisitorController extends Controller
{
    /**
     * @var VisitorContract
     */
    protected $visitorContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VisitorContract $visitorContract)
    {
        $this->visitorContract = $visitorContract;
    }

    /**
     * Get visitor page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVisitorPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.visitor', $varsForView);
    }

    /**
     * Get tourist page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTouristPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.tourist', $varsForView);
    }
}
