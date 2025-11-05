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
        Schema::table('categories', function (Blueprint $table) {
            // Kolom ini akan menampung ID dari Kategori Induk
            $table->foreignId('parent_id')
                ->nullable() // Boleh kosong (jika dia adalah Induk)
                ->after('id') // Letakkan di dekat 'id' agar rapi
                ->constrained('categories') // Merujuk ke tabel 'categories' itu sendiri
                ->onDelete('set null'); // Jika Induk dihapus, jadikan Anaknya top-level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
