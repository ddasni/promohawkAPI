<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function build()
    {
        return $this->subject('Senha Redefinida com Sucesso')
                    ->view('emails.password-reset-success')
                    ->with([
                        'nome' => $this->nome,
                    ]);
    }
}
