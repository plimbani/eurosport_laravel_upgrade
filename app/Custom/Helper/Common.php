<?php

namespace App\Custom\Helper;

use App\Exports\ReportExport;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Common
{
    public static function toExcel($lableArray, $dataArray, $otherParams, $output = 'xlsx', $download = 'yes', $columnFormat = '')
    {

        return \Excel::download(new ReportExport($lableArray, $dataArray, $otherParams, $output = 'xlsx', $download = 'yes', $columnFormat = ''), 'report.xlsx');

        /*$excelCreateObj = \Excel::create(Str::slug($otherParams['sheetTitle']), function ($excel) use ($lableArray, $dataArray, $otherParams, $columnFormat) {
            $excel->setTitle($otherParams['sheetTitle']);
            $excel->sheet($otherParams['sheetName'], function ($sheet) use ($lableArray, $dataArray, $otherParams, $columnFormat) {
                $sheet->row(1, $lableArray);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#1f844c');
                    $row->setFontColor('#ffffff');
                    $row->setFontWeight('bold');
                    $row->setFontFamily('Arial');
                    $row->setFontSize(10);
                });
                if ($columnFormat != '') {
                    $sheet->setColumnFormat($columnFormat);
                }
                $row_no = 2;
                foreach ($dataArray as $data) {

                    $sheet->row($row_no, $data);
                    $row_no++;
                }

                if ($otherParams['boldLastRow']) {
                    $sheet->row(($row_no - 1), function ($row) {
                        $row->setFontWeight('bold');
                    });
                }
            });
        });

        $download == 'yes' ? $excelCreateObj->export($output) : $excelCreateObj->store($output);*/

    }

    public static function sendMail($email_details, $email_recipients, $email_subject, $email_view, $email_from = null)
    {
        $contact_details = $email_details;
        $recipient = $email_recipients;
        if ($email_from != null && ! empty($email_from)) {
            Mail::to($recipient)->send(new SendMail($contact_details, $email_subject, $email_view, $email_from));
        } else {
            Mail::to($recipient)->send(new SendMail($contact_details, $email_subject, $email_view));
        }

        return response()->json([
            'status' => 'suceess',
            'message' => 'Thank you for your message. We will aim to get back to you within the next 24 hours.',
        ]);
    }

    public static function addSchemeToUrl($url, $scheme = 'http://')
    {
        if ($url == '') {
            return $url;
        }

        return parse_url($url, PHP_URL_SCHEME) === null ? $scheme.$url : $url;
    }
}
