<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemProduto extends Model
{
    use HasFactory;

    protected $table = 'imagem_produto';

    protected $fillable = [
        'produto_id',
        'imagem',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
