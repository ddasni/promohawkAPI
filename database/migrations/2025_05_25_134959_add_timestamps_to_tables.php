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
        // Schema::table('produto', function (Blueprint $table) {
        // $table->timestamps();
        // });

        // Schema::table('categoria', function (Blueprint $table) {
        //     $table->timestamps();
        // });

        // Schema::table('loja', function (Blueprint $table) {
        //     $table->timestamps();
        // });

        // Schema::table('preco_produto', function (Blueprint $table) {
        //     $table->timestamps();
        // });

        // Schema::table('review', function (Blueprint $table) {
        //     $table->timestamps();
        // });

        // Schema::table('favoritos', function (Blueprint $table) {
        //     $table->timestamps();
        // });

        // Schema::table('cupom', function (Blueprint $table) {
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
        $table->dropTimestamps();
        });

        Schema::table('categoria', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('loja', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('preco_produto', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('review', function (Blueprint $table) {
        $table->dropTimestamps();
        });

        Schema::table('favoritos', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('cupom', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
