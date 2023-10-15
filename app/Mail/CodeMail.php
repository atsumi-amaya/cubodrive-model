<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'CuboDrive: Codigo de cliente',
        );
    }

    public function content()
    {
        return new Content(
            view: 'user.code.mail',
            with: [
                'code' => $this->code,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
