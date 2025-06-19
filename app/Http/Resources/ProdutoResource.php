<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrecoProdutoResource;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ReviewResource;

class ProdutoResource extends JsonResource
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
            'status_produto' => $this->status_produto,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'media_nota' => round($this->reviews->avg('avaliacao_produto'), 1),
            'link' => $this->link,
            'imagens' => $this->imagens->pluck('imagem'),

            // Resources específicos
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'precos' => PrecoProdutoResource::collection($this->whenLoaded('precos')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
