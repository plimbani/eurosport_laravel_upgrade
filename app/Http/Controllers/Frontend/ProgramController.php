<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\ProgramContract;

class ProgramController extends Controller
{
    /**
     * @var ProgramContract
     */
    protected $programContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProgramContract $programContract)
    {
        $this->programContract = $programContract;
    }

    /**
     * Get program page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProgramPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.program', $varsForView);
    }

    /**
     * Get program additional page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdditionalProgramPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.program', $varsForView);
    }
}
