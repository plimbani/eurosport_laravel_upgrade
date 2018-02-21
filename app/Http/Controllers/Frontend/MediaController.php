<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Contracts\MediaContract;

class MediaController extends Controller
{
    /**
     * @var MediaContract
     */
    protected $mediaContract;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MediaContract $mediaContract)
    {
        $this->mediaContract = $mediaContract;
    }

    /**
     * Get media page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMediaPageDetails(Request $request)
    {
        $varsForView = [];

        return view('frontend.media', $varsForView);
    }
}
