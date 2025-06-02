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
        
        // // Tabela de favoritos
        // Schema::create('favoritos', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('usuario_id');
        //     $table->unsignedBigInteger('produto_id');
        //     $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('produto_id')->references('id')->on('produto')->onDelete('cascade');
        //     $table->unique(['usuario_id', 'produto_id']);
        //     $table->timestamps();
        // });


        // // Tabela de review
        // Schema::create('review', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('produto_id');
        //     $table->unsignedBigInteger('usuario_id');
        //     $table->foreign('produto_id')->references('id')->on('produto')->onDelete('cascade');
        //     $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->integer('avaliacao_produto');
        //     $table->text('comentario_produto');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritos');
        Schema::dropIfExists('review');
    }
};
