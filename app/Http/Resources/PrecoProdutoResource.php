<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LojaResource;

class PrecoProdutoResource extends JsonResource
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
            'produto_id' => $this->produto_id,
            'preco' => number_format($this->preco, 2, ',', '.'),
            'forma_pagamento' => $this->forma_pagamento,
            'parcelas' => $this->parcelas,
            'valor_parcela' => number_format($this->valor_parcela, 2, ',', '.'),
            'data_registro' => $this->created_at->format('d/m/Y H:i'),
            'data_alteração' => $this->updated_at->format('d/m/Y H:i'),
            'loja' => new LojaResource($this->whenLoaded('loja')),
        ];
    }
}
