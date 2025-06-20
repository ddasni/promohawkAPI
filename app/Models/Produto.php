<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    /** @use HasFactory<\Database\Factories\ProdutoFactory> */
    use HasFactory;

    protected $table = 'produto';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'categoria_id',
        'nome',
        'descricao',
        'link',
        'status_produto'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function precos()
    {
        return $this->hasMany(PrecoProduto::class);
    }

    public function imagens()
    {
        return $this->hasMany(ImagemProduto::class, 'produto_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Verificar isso
    public function favoritadoPor()
    {
        return $this->belongsToMany(User::class, 'favoritos', 'produto_id', 'usuario_id');
    }
}
