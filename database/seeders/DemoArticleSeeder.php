<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag; // Pastikan ini model manual kita 'App\Models\Tag'
use Illuminate\Support\Str;

class DemoArticleSeeder extends Seeder
{
    /**
     * Jalankan seeder artikel demo Sumba.
     */
    public function run(): void
    {
        // 1. Cari Penulis (Admin/Editor)
        $author = User::where('role', 'admin')->first() ?? User::factory()->admin()->create();

        // 2. Ambil Kategori yang Relevan
        // (Kita asumsikan LiveSeeder sudah membuat ini)
        $nasionalCategory = Category::where('slug', 'nasional')->first() ?? Category::first();
        $ekonomiCategory = Category::where('slug', 'ekonomi')->first() ?? $nasionalCategory;
        $lingkunganCategory = Category::where('slug', 'energi-lingkungan')->first() ?? $nasionalCategory;
        $olahragaCategory = Category::where('slug', 'olahraga')->first() ?? $nasionalCategory;

        // 3. Daftar 11 Judul Berita Sumba (Termasuk Breaking News)
        $titles = [
            1 => ['title' => 'Festival Pasola Berdarah: Tradisi vs. Keamanan Modern di Sumba Barat', 'category' => $olahragaCategory, 'tags' => ['Berita Sumba', 'Pasola', 'Sumba Barat', 'Budaya']],
            2 => ['title' => 'Kekeringan Melanda Sumba Timur, Petani Terancam Gagal Panen Jagung', 'category' => $nasionalCategory, 'tags' => ['Berita Sumba', 'Sumba Timur', 'Kekeringan']],
            3 => ['title' => 'Tenun Ikat Sumba Mendunia: Perajin Lokal Tembus Pasar Eropa', 'category' => $ekonomiCategory, 'tags' => ['Ekonomi', 'Tenun Ikat', 'Berita Sumba']],
            4 => ['title' => 'Pembangunan Resor di Sumba Barat Daya Picu Perdebatan Lahan Adat', 'category' => $nasionalCategory, 'tags' => ['Berita Sumba', 'Sumba Barat Daya', 'Pariwisata']],
            5 => ['title' => 'Jalan Trans-Sumba Rusak Parah di Anakalang, Logistik Terhambat', 'category' => $nasionalCategory, 'tags' => ['Infrastruktur', 'Sumba Tengah', 'Berita Sumba']],
            6 => ['title' => 'Potensi Ekowisata Danau Weekuri: Surga Tersembunyi SBD Butuh Pengelolaan Serius', 'category' => $ekonomiCategory, 'tags' => ['Pariwisata NTT', 'Sumba Barat Daya']],
            7 => ['title' => 'Polres Sumba Barat Tangkap Komplotan Pencuri Ternak yang Resahkan Warga', 'category' => $nasionalCategory, 'tags' => ['Kriminal', 'Pencurian Ternak', 'Sumba Barat']],
            8 => ['title' => 'Proyek \'Sumba Iconic Island\' Dievaluasi, Apa Kabar Kedaulatan Energi Terbarukan?', 'category' => $lingkunganCategory, 'tags' => ['Energi Terbarukan', 'Sumba Iconic Island', 'NTT']],
            9 => ['title' => 'Wabah Malaria Kembali Muncul di Sumba Tengah, Dinkes Lakukan Fogging Massal', 'category' => $nasionalCategory, 'tags' => ['Kesehatan', 'Malaria', 'Sumba Tengah']],
            10 => ['title' => 'Kuda Sandelwood, Ikon Sumba yang Mulai Terlupakan Akibat Modernisasi', 'category' => $nasionalCategory, 'tags' => ['Budaya', 'Berita Sumba', 'Kuda Sandelwood']],

            // ================ INI ARTIKEL BARU KITA ================
            11 => [
                'title' => 'BREAKING: Erupsi Gunung Lewotobi, Warga Diimbau Menjauh 5 KM',
                'category' => $lingkunganCategory,
                'tags' => ['Erupsi Lewotobi', 'Berita Sumba', 'Bencana Alam', 'NTT']
            ]
        ];

        // 4. Buat Semua Tag (Logika ini akan otomatis mengambil 'Erupsi Lewotobi')
        $allTags = collect($titles)->flatMap(fn($item) => $item['tags'])->unique();
        $tagModels = collect();
        foreach ($allTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagModels->put($tagName, $tag->id); // Simpan ID-nya
        }


        // 5. Buat 11 Artikel menggunakan Factory (untuk body/excerpt)
        foreach ($titles as $key => $item) {
            $article = Article::factory()->create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'user_id' => $author->id,
                'category_id' => $item['category']->id,
                'status' => Article::STATUS_PUBLISHED,
                // PENTING: Jadikan artikel #11 (Breaking News) yang PALING BARU
                'published_at' => ($key == 11) ? now() : now()->subDays(rand(1, 30)),
            ]);

            // 6. Lampirkan Tag (sistem manual)
            $tagIdsToAttach = collect($item['tags'])->map(fn($tagName) => $tagModels->get($tagName));
            $article->tags()->sync($tagIdsToAttach);
        }
    }
}
