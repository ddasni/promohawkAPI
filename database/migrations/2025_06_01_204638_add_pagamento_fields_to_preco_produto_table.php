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
        // Schema::table('preco_produto', function (Blueprint $table) {
        //     $table->string('forma_pagamento')->nullable();
        //     $table->integer('parcelas')->nullable();
        //     $table->decimal('valor_parcela', 10, 2)->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preco_produto', function (Blueprint $table) {
            $table->dropColumn(['forma_pagamento', 'parcelas', 'valor_parcela']);
        });
    }
};
