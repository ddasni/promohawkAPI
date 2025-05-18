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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
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


        // Schema::create('users', function (Blueprint $table) {
        //     $table->increments('id_usuario'); // increments -> chave primária auto_increment
        //     $table->string('username', 30);
        //     $table->string('nome', 100);
        //     $table->string('telefone', 15);
        //     $table->string('email', 100)->unique();
        //     $table->timestamp('email_verified_at')->nullable(); // Marca quando o e-mail foi verificado
        //     $table->string('password', 170);
        //     $table->string('imagem')->nullable(); // caminho para a imagem
        //     $table->rememberToken(); // Guardar um token para login persistente (lembre-se de mim)
        //     $table->timestamps(); // adiciona created_at e updated_at, ou seja, data e hora
        //              // em em que o registro foi criado e última atualização desse registro
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
