<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'link' => $this->link,
            'status_produto' => $this->status_produto,
            'media_nota' => round($this->reviews->avg('nota'), 1),
            'loja' => [
                'id' => $this->loja->id,
                'nome' => $this->loja->nome,
                'imagem' => $this->loja->imagem,
            ],
            'categoria' => [
                'id' => $this->categoria->id,
                'nome' => $this->categoria->nome,
                'imagem' => $this->categoria->imagem,
            ],
            'imagens' => $this->imagens->pluck('imagem'),
            'precos' => $this->precos->map(function ($preco) {
                return [
                    'preco' => number_format($preco->preco, 2, ',', '.'),
                    'forma_pagamento' => $preco->forma_pagamento,
                    'parcelas' => $preco->parcelas,
                    'valor_parcela' => number_format($preco->valor_parcela, 2, ',', '.'),
                    'data_registro' => $preco->created_at->format('d/m/Y H:i'),
                    'data_alteração' => $preco->updated_at->format('d/m/Y H:i'),
                    'loja' => [
                        'id' => $preco->loja->id,
                        'nome' => $preco->loja->nome,
                    ]
                ];
            }),
            'reviews' => $this->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'nota' => $review->avaliacao_produto,
                    'comentario_produto' => $review->comentario_produto,
                    'usuario' => [
                        'id' => $review->usuario->id,
                        'username' => $review->usuario->username,
                        'imagem' => $review->usuario->imagem
                    ]
                ];
            }),
        ];
    }
}
