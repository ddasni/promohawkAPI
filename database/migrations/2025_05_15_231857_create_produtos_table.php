<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // increments -> chave primária auto_increment
            $table->unsignedBigInteger('preco_id');
            $table->string('username', 30);
            $table->string('nome', 100);
            $table->string('telefone', 15);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // Marca quando o e-mail foi verificado
            $table->string('password', 170);
            $table->string('imagem')->nullable(); // caminho para a imagem
            $table->rememberToken(); // Guardar um token para login persistente (lembre-se de mim)
            $table->timestamps(); // adiciona created_at e updated_at, ou seja, data e hora
                     // em em que o registro foi criado e última atualização desse registro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
