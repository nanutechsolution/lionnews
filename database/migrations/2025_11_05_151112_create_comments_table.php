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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Link ke tabel 'articles'
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');

            // Link ke tabel 'users' (siapa yang menulis)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Isi komentar
            $table->text('body');

            $table->timestamps(); // Kapan dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
