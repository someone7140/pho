<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationNotification extends Mailable
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
        return $this->view('emails.email_verification')
                    ->subject(config('const_mail.EMAIL_VERIFICAITON_TITLE'))
                    ->with([
                        'velificationLink' => $this->velificationLink,
                      ]);
    }
}
