<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    public function index()
    {
        flash('Welcome to Laraspace')->success();

        return view('front.index');
    }
}
