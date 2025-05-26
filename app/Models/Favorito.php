<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorito extends Model
{
    /** @use HasFactory<\Database\Factories\FavoritoFactory> */
    use HasFactory;

    protected $table = 'favoritos';


    protected $fillable = [
        'usuario_id',
        'produto_id',
    ];


    /**
     * Usuário que favoritou.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Produto favoritado.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
