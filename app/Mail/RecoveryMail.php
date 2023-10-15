<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    public function __construct(string $post)
    {
        $this->post = $post;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Correo de cambio de contraseña',
        );
    }

    public function content()
    {
        return new Content(
            view: 'user.pass.email',
            with: [
                'post' => $this->post,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
