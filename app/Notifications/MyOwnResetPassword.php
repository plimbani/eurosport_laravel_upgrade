<?php

namespace Laraspace\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laraspace\Notifications\Messages\MailMessage;
use Laraspace\Models\User;

class MyOwnResetPassword extends Notification
{
    use Queueable;

   /**
    * The password reset token.
    *
    * @var string
    */
    public $token;
    protected $name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      // ->action('Notification Action', url('/'))
        return (new MailMessage)
                    ->subject("Euro-Sportring Tournament Planner - Password Reset")
                    ->line('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:')
                    ->action('Reset passwsord', route('password.reset', $this->token))
                    ->view('notifications::email',array('name'=>$this->name))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
