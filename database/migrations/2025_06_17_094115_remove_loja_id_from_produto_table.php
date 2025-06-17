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
        Schema::table('produto', function (Blueprint $table) {
            $table->dropForeign(['loja_id']); // Se tiver constraint
            $table->dropColumn('loja_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->foreignId('loja_id')->constrained('loja');
        });
    }
};
