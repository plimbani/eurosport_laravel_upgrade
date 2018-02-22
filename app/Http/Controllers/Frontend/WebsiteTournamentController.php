<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\WebsiteTournamentContract;

class WebsiteTournamentController extends Controller
{
    /**
     * @var WebsiteTournamentContract
     */
    protected $websiteTournamentContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WebsiteTournamentContract $websiteTournamentContract)
    {
        $this->websiteTournamentContract = $websiteTournamentContract;
    }

    /**
     * Get tournament page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTournamentPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.tournament', $varsForView);
    }

    /**
     * Get rules page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRulesPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.rules', $varsForView);
    }

    /**
     * Get history page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistoryPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.history', $varsForView);
    }
}
