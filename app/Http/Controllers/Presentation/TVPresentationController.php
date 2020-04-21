<?php

namespace Laraspace\Http\Controllers\Presentation;

use Landlord;
use Illuminate\Http\Request;
use Laraspace\Api\Services\PageService;

class TVPresentationController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

	public function showPresentation()
	{
		return view('tvpresentation/pages.presentation.show');
	}
}