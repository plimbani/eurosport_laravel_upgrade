<?php

namespace Laraspace\Http\Controllers;

use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function getFooter(Request $request)
    {	
      $query = $request->all();
      return view('summary.footer', compact('query'));	
    }
}
