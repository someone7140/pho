<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $velificationLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($velificationLink)
    {
        $this->velificationLink = $velificationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reset')
                    ->subject(config('const_mail.EMAIL_PASSWORD_RESET_TITLE'))
                    ->with([
                        'velificationLink' => $this->velificationLink,
                      ]);
    }
}
