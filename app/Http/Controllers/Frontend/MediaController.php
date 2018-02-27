<?php

namespace Laraspace\Http\Controllers\Frontend;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;
use Laraspace\Api\Contracts\MediaContract;

class MediaController extends Controller
{
    /**
     * @var MediaContract
     */
    protected $mediaContract;

    /**
     * @var Media page name
     */
    protected $mediaPageName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MediaContract $mediaContract, PageService $pageService)
    {
        $this->pageService = $pageService;
        $this->mediaContract = $mediaContract;
        $this->mediaPageName = 'media';
    }

    /**
     * Get media page details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMediaPageDetails(Request $request)
    {
        $varsForView = [];
        $websiteId = Landlord::getTenants()['website']->id;
        $pageDetail = $this->pageService->getPageDetails($this->mediaPageName, $websiteId);

        // Page title
        $varsForView['pageTitle'] = $pageDetail->title;

        return view('frontend.media', $varsForView);
    }
}
