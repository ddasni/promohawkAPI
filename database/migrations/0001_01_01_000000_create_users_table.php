<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Criar as tabelas
     */
    public function up(): void
    {
        // // Tabela de administradores
        // Schema::create('adm', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nome', 100);
        //     $table->string('email', 100)->unique();
        //     $table->string('password', 170);
        //     $table->timestamps();
        // });


        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('username', 30)->unique();
        //     $table->string('nome', 100);
        //     $table->string('telefone', 15);
        //     $table->string('email', 100)->unique();
        //     $table->timestamp('email_verified_at')->nullable(); // Marca quando o e-mail foi verificado
        //     $table->string('password', 170);
        //     $table->text('imagem')->nullable(); // caminho para a imagem
        //     $table->rememberToken(); // Guardar um token para login persistente (lembre-se de mim)
        //     $table->timestamps(); // adiciona created_at e updated_at, ou seja, data e hora
        //              // em em que o registro foi criado e última atualização desse registro
        // });


        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });  
    }



    /**
     * Reverse the migrations.
     * Apagar as tabelas
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('adm');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};