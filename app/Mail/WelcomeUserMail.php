<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $nome) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Bem-vindo ao Promohawk!');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-user',
            with: ['nome' => $this->nome],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
