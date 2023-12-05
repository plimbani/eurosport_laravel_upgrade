<?php

namespace App\Notifications;

use App\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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

    protected $userEmail;

    protected $userOtp;

    protected $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $name, $userEmail, $userOtp, $subject, $currentLayout)
    {
        $this->token = $token;
        $this->name = $name;
        $this->userEmail = $userEmail;
        $this->userOtp = $userOtp;
        $this->subject = $subject;
        $this->currentLayout = $currentLayout;

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
        return (new MailMessage)
            ->subject($this->subject)
            ->line('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:')
            ->action('Reset passwsord', url('password/reset', $this->token))
            ->view('notifications::email', ['name' => $this->name, 'userEmail' => urlencode($this->userEmail), 'userOTP' => $this->userOtp, 'currentLayout' => $this->currentLayout])
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
