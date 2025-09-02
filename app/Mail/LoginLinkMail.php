<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $loginUrl;

    public function __construct(string $loginUrl)
    {
        $this->loginUrl = $loginUrl;
    }

    public function build(): self
    {
        return $this->subject('Link Login Presensi PKL')
            ->view('emails.login_link');
    }
}
