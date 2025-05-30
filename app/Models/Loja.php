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
        return $this->hasMany(Produto::class);
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
