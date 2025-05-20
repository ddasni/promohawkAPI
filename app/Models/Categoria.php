<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $table = 'categoria';

    protected $fillable = [
        'nome',
        'imagem',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
