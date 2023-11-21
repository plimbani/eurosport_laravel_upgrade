<?php

namespace Laraspace\Http\Controllers;

class FrontendController extends Controller
{
    public function index()
    {
        flash()->success('Welcome to Laraspace');

        return view('front.index');
    }
}
