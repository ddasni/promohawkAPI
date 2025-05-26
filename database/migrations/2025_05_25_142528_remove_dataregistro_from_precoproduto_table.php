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
        Schema::table('preco_produto', function (Blueprint $table) {
            $table->dropColumn('data_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preco_produto', function (Blueprint $table) {
            $table->string('data_registro')->nullable(); // ou ajuste conforme o tipo original
        });
    }
};
