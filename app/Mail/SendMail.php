<?php

namespace Laraspace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
   use Queueable, SerializesModels;
   
   public $email_details;
   public $view_name;
   public $from_email;
   public $subject = "Eurosport | Support";
   public $reply_to_email;
   public $reply_to_name;
   public $attachment;

   /**
    * Create a new message instance.
    *
    * @return void
    */

   public function __construct($email_details, $subject, $view_name, $from_email = null, $reply_to_email = null, $reply_to_name = null, $file = null)
   {
       $this->subject = $subject;
       $this->view_name = $view_name;
       $this->email_details = $email_details;
       $this->from_email = $from_email;
       $this->reply_to_email = $reply_to_email;
       $this->reply_to_name = $reply_to_name;
       $this->file = $file;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
        if (!empty($this->from_email)) {
            $this->from($this->from_email);
        }
        if (!empty($this->reply_to_email)) {
            $this->replyTo($this->reply_to_email, $this->reply_to_name);
        }


        if($this->file != null) {
            return $this->view($this->view_name)->attach($this->file);
        } else {
            return $this->view($this->view_name);
        }
   }
}