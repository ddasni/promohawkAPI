<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transforma o recurso em array para a resposta JSON do login ou /auth/me.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'nome' => $this->nome,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'imagem' => $this->imagem ? asset($this->imagem) : null,
            'remember_token' => $this->remember_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
