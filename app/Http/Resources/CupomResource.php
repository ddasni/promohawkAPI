<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CupomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'descricao' => $this->descricao,
            'desconto' => $this->desconto,
            'validade' => $this->validade ? $this->validade->format('d/m/Y H:i:s') : null,
            
            // Mostrando o nome da loja relacionada
            'loja' => $this->loja ? $this->loja->nome : null,
        ];
    }
}
