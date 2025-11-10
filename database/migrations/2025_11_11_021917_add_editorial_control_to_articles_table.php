<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Untuk 1 slot HERO utama
            $table->boolean('is_hero_pinned')->default(false)->after('status');

            // Untuk 4 slot SLIDER "Pilihan Editor"
            $table->boolean('is_editors_pick')->default(false)->after('is_hero_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
};
