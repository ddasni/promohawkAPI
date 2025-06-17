<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avaliacao_produto' => $this->avaliacao_produto,
            'comentario_produto' => $this->comentario_produto,
            'created_at' => $this->created_at->format('d/m/Y H:i'),

            'produto' => new ProdutoResource($this->whenLoaded('produto')),
            'usuario' => new UserResource($this->whenLoaded('usuario')),
        ];
    }
}
