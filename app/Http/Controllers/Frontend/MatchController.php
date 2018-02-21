<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\MatchContract;

class MatchController extends Controller
{
    /**
     * @var MatchContract
     */
    protected $matchContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MatchContract $matchContract)
    {
        $this->matchContract = $matchContract;
    }

    /**
     * Get matches page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMatchPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.matches', $varsForView);
    }
}
