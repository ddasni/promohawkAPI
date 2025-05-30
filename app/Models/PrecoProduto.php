<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecoProduto extends Model
{
    /** @use HasFactory<\Database\Factories\HistoricoPrecoFactory> */
    use HasFactory;

    protected $table = 'preco_produto';

    protected $fillable = [
        'produto_id',
        'loja_id',
        'preco',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
