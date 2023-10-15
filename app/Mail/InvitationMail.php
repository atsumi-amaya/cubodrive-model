<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $pass;

    public function __construct(string $name, string $email, string $pass)
    {
        $this->name = $name;
        $this->email = $email;
        $this->pass = $pass;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Invitacion a Cubodrive',
        );
    }

    public function content()
    {
        return new Content(
            view: 'user.mail.invitation',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'pass' => $this->pass,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}