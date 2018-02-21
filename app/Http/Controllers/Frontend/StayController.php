<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\StayContract;

class StayController extends Controller
{
    /**
     * @var StayContract
     */
    protected $stayContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StayContract $stayContract)
    {
        $this->stayContract = $stayContract;
    }

    /**
     * Get stay page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStayPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.stay', $varsForView);
    }

    /**
     * Get meals page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMealsPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.meals', $varsForView);
    }

    /**
     * Get accommodation page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccommodationPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.accommodation', $varsForView);
    }

    /**
     * Get additional stay page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdditionalStayPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.stay', $varsForView);
    }
}
