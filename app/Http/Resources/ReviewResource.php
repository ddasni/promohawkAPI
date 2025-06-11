<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'produto_id' => $this->produto_id,
            'usuario_id' => $this->usuario_id,
            'avaliacao_produto' => $this->avaliacao_produto,
            'comentario_produto' => $this->comentario_produto,
            'created_at' => $this->created_at,
            'usuario' => new UserResource($this->whenLoaded('usuario')),
        ];
    }
}
