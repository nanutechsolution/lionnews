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
        $nasionalCategory = Category::where('slug', 'nasional')->first();
        $ekonomiCategory = Category::where('slug', 'ekonomi')->first();
        $olahragaCategory = Category::where('slug', 'olahraga')->first();
        $teknologiCategory = Category::where('slug', 'teknologi')->first();

        // Jika tidak ada, ambil saja kategori acak
        if (!$nasionalCategory) {
            $nasionalCategory = Category::first();
        }

        // 3. Daftar 10 Judul Berita Sumba yang Realistis
        $titles = [
            1 => [
                'title' => 'Festival Pasola Berdarah: Tradisi vs. Keamanan Modern di Sumba Barat',
                'category' => $olahragaCategory ?? $nasionalCategory,
                'tags' => ['Berita Sumba', 'Pasola', 'Sumba Barat', 'Budaya']
            ],
            2 => [
                'title' => 'Kekeringan Melanda Sumba Timur, Petani Terancam Gagal Panen Jagung',
                'category' => $nasionalCategory,
                'tags' => ['Berita Sumba', 'Sumba Timur', 'Kekeringan', 'Pertanian']
            ],
            3 => [
                'title' => 'Tenun Ikat Sumba Mendunia: Perajin Lokal Tembus Pasar Eropa',
                'category' => $ekonomiCategory ?? $nasionalCategory,
                'tags' => ['Ekonomi', 'Tenun Ikat', 'UMKM', 'Berita Sumba']
            ],
            4 => [
                'title' => 'Pembangunan Resor di Sumba Barat Daya Picu Perdebatan Lahan Adat',
                'category' => $nasionalCategory,
                'tags' => ['Berita Sumba', 'Sumba Barat Daya', 'Pariwisata', 'Konflik Lahan']
            ],
            5 => [
                'title' => 'Jalan Trans-Sumba Rusak Parah di Anakalang, Pengiriman Logistik Terhambat',
                'category' => $nasionalCategory,
                'tags' => ['Infrastruktur', 'Sumba Tengah', 'Berita Sumba']
            ],
            6 => [
                'title' => 'Potensi Ekowisata Danau Weekuri: Surga Tersembunyi SBD Butuh Pengelolaan Serius',
                'category' => $ekonomiCategory ?? $nasionalCategory,
                'tags' => ['Pariwisata NTT', 'Sumba Barat Daya', 'Berita Sumba']
            ],
            7 => [
                'title' => 'Polres Sumba Barat Tangkap Komplotan Pencuri Ternak yang Resahkan Warga',
                'category' => $nasionalCategory,
                'tags' => ['Kriminal', 'Pencurian Ternak', 'Sumba Barat']
            ],
            8 => [
                'title' => 'Proyek \'Sumba Iconic Island\' Dievaluasi, Apa Kabar Kedaulatan Energi Terbarukan?',
                'category' => $teknologiCategory ?? $nasionalCategory,
                'tags' => ['Energi Terbarukan', 'Sumba Iconic Island', 'NTT']
            ],
            9 => [
                'title' => 'Wabah Malaria Kembali Muncul di Sumba Tengah, Dinkes Lakukan Fogging Massal',
                'category' => $nasionalCategory,
                'tags' => ['Kesehatan', 'Malaria', 'Sumba Tengah']
            ],
            10 => [
                'title' => 'Kuda Sandelwood, Ikon Sumba yang Mulai Terlupakan Akibat Modernisasi',
                'category' => $nasionalCategory,
                'tags' => ['Budaya', 'Berita Sumba', 'Kuda Sandelwood']
            ],
        ];

        // 4. Buat Tag Terlebih Dahulu (menggunakan sistem manual kita)
        $allTags = collect($titles)->flatMap(fn($item) => $item['tags'])->unique();
        $tagModels = collect();
        foreach ($allTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagModels->put($tagName, $tag->id); // Simpan ID-nya
        }


        // 5. Buat 10 Artikel menggunakan Factory (untuk body/excerpt)
        foreach ($titles as $item) {
            $article = Article::factory()->create([
                'title' => $item['title'], // Ganti judul dari factory
                'slug' => Str::slug($item['title']),
                'user_id' => $author->id,
                'category_id' => $item['category']->id,
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now()->subDays(rand(1, 30)), // Terbit acak 30 hari terakhir
            ]);

            // 6. Lampirkan Tag (sistem manual)
            $tagIdsToAttach = collect($item['tags'])->map(fn($tagName) => $tagModels->get($tagName));
            $article->tags()->sync($tagIdsToAttach);
        }
    }
}