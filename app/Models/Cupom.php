<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    /** @use HasFactory<\Database\Factories\CupomFactory> */
    use HasFactory;

    protected $table = 'cupom';

    protected $fillable = [
        'loja_id',
        'codigo',
        'descricao',
        'desconto',
        'validade',
        'status_cupom',
    ];

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
