<?php
namespace Laraspace\Custom\Helper;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class Common {

    static function toExcel($lableArray, $dataArray, $otherParams,$output = 'xlsx',$download='yes',$columnFormat=''){
        $excelCreateObj = \Excel::create(str_slug($otherParams['sheetTitle']), function($excel) use($lableArray, $dataArray, $otherParams,$columnFormat) {
            $excel->setTitle($otherParams['sheetTitle']);
            $excel->sheet($otherParams['sheetName'], function($sheet) use($lableArray, $dataArray, $otherParams,$columnFormat) {
                $sheet->row(1, $lableArray);
                $sheet->row(1, function($row){
                    $row->setBackground("#1f844c");
                    $row->setFontColor('#ffffff');
                    $row->setFontWeight('bold');
                    $row->setFontFamily('Arial');
                    $row->setFontSize(10);
                });
                 if($columnFormat!=''){
                $sheet->setColumnFormat($columnFormat); 
                 }
                $row_no = 2;
                foreach ($dataArray as $data) {
                     
                    $sheet->row($row_no, $data);
                    $row_no++;
                }
                
                if($otherParams['boldLastRow']){
                    $sheet->row(($row_no-1), function($row){
                        $row->setFontWeight('bold');
                    });
                }
            });
        });
         $excelCreateObj->export($output);   
       
    }
}