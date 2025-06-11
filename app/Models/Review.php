<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $table = 'review';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'produto_id',
        'usuario_id',
        'avaliacao_produto',
        'comentario_produto',
    ];

    public function Produto()
    {
        return $this->belongsTo(Produto::class);
    }

   public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

}
