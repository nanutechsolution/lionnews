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
       Schema::create('articles', function (Blueprint $table) {
    $table->id();

    // ----- Relasi Kunci -----
    // Relasi ke tabel 'users' (Siapa Penulisnya)
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

    // Relasi ke tabel 'categories' (Masuk Kategori Apa)
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

    // ----- Konten Artikel -----
    $table->string('title'); // Judul Berita
    $table->string('slug')->unique(); // Untuk URL SEO
    $table->text('excerpt'); // Kutipan/Lead singkat
    $table->longText('body'); // Isi artikel (dari Rich Text Editor)
    $table->string('featured_image_path')->nullable(); // Path ke gambar utama

    // ----- Status & SEO (Penting untuk Editor) -----
    $table->string('status')->default('draft'); // Cth: draft, pending_review, published
    $table->timestamp('published_at')->nullable(); // Untuk jadwal terbit
    $table->string('meta_description')->nullable();
    $table->string('meta_keywords')->nullable();

    $table->timestamps(); // Kapan dibuat (created_at) & diubah (updated_at)
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
