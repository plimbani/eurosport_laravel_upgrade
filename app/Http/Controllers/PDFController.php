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

    public function getMatchSchedulePdfFooter(Request $request)
    {
        $query = $request->all();
        return view('age_category.match_schedule_pdf_footer', compact('query'));
    }
}
