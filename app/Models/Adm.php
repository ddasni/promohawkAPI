<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Adm extends Authenticatable
{
    protected $table = 'adm';

    protected $fillable = [
        'nome',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
