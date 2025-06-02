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
        // // cria a tabela Loja
        // Schema::create('loja', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nome', 100)->unique();
        //     $table->text('imagem')->nullable();
        //     $table->timestamps();
        // });


        // // cria a tabela categoria
        // Schema::create('categoria', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nome', 100)->unique();
        //     $table->text('imagem')->nullable();
        //     $table->timestamps();
        // });


        // // cria a tabela produto
        // Schema::create('produto', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('loja_id')->constrained('loja');
        //     $table->foreignId('categoria_id')->constrained('categoria');
        //     $table->string('nome', 100);
        //     $table->string('descricao', 200)->default('não possui descrição');
        //     $table->text('link');
        //     $table->string('status_produto', 15)->default('ativo');
        //     $table->timestamps();
        // });


        // // cria a tabela imagem_produto
        // Schema::create('imagem_produto', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('produto_id')->constrained('produto');
        //     $table->text('imagem');
        //     $table->timestamps();
        // });


        // // cria a tabela preco_produto
        // Schema::create('preco_produto', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('produto_id')->constrained('produto')->onDelete('cascade');
        //     $table->foreignId('loja_id')->constrained('loja');
        //     $table->decimal('preco', 7, 2);
        //     $table->string('forma_pagamento', 50)->nullable(); // Ex: "boleto", "cartao", "pix"
        //     $table->integer('parcelas')->nullable();           // Ex: 10
        //     $table->decimal('valor_parcela', 7, 2)->nullable(); // Ex: 49.90
        //     $table->timestamps();
        // });


        // // cria a tabela cupom
        // Schema::create('cupom', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('loja_id')->constrained('loja');
        //     $table->string('codigo', 50)->unique();
        //     $table->decimal('desconto', 5, 2);
        //     $table->dateTime('validade');
        //     $table->string('status_cupom', 15)->default('ativo');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     * Apagar as tabelas
     */
    public function down(): void
    {
        Schema::dropIfExists('preco_produto');
        Schema::dropIfExists('produto');
        Schema::dropIfExists('categoria');
        Schema::dropIfExists('loja');
    }
};
