<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    /** @use HasFactory<\Database\Factories\LojaFactory> */
    use HasFactory;

    protected $table = 'loja';

    protected $fillable = [
        'nome',
        'imagem',
    ];

    public function produtos()
    {
        return $this->hasManyThrough(
            \App\Models\Produto::class,
            \App\Models\PrecoProduto::class,
            'loja_id',      // foreign key em preco_produto
            'id',           // chave primária em produto (referenciada via preco_produto.produto_id)
            'id',           // chave primária da loja (local)
            'produto_id'    // foreign key para produto em preco_produto
        )->distinct(); // caso queira evitar duplicação se tiver múltiplos preços para um mesmo produto
    }

    public function precos()
    {
        return $this->hasMany(PrecoProduto::class);
    }

    public function cupons()
    {
        return $this->hasMany(Cupom::class);
    }
}
