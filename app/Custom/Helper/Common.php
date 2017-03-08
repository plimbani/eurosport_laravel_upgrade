<?php
namespace App\Custom\Helper;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class Common {
    /**
     * Send mail
     * @param  [type] $email_details    Email detail
     * @param  [type] $email_recipients Email recipients
     * @param  [type] $email_subject    Email subject
     * @param  [type] $email_view       Email view
     * @return JSON
     */
    static function sendMail($email_details, $email_recipients, $email_subject, $email_view)
    {        
        $contact_details = $email_details;
        $recipient = $email_recipients;
        Mail::to($recipient)->send(new SendMail($contact_details, $email_subject,  $email_view));
        return response()->json([
            'status' => 'suceess'
        ]);
    }
}