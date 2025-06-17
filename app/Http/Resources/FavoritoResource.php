<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoritoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuario_id,
            'produto_id' => $this->produto_id,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),

            // Relacionamento com Produto e Preco dele
            'produto' => new ProdutoResource($this->whenLoaded('produto')),
            'precos' => PrecoProdutoResource::collection($this->whenLoaded('precos'))
        ];
    }
}
