<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyOnImagemProdutoTable extends Migration
{
    public function up(): void
    {
        Schema::table('imagem_produto', function (Blueprint $table) {
            // Primeiro, removemos a foreign key antiga
            $table->dropForeign(['produto_id']);

            // Depois, adicionamos novamente com onDelete('cascade')
            $table->foreign('produto_id')
                  ->references('id')
                  ->on('produto')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('imagem_produto', function (Blueprint $table) {
            // Reverte para o estado anterior (sem cascade)
            $table->dropForeign(['produto_id']);
            $table->foreign('produto_id')
                  ->references('id')
                  ->on('produto');
        });
    }
}