<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Tag; // Pastikan ini 'App\Models\Tag' kustom kita
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // <-- 1. PASTIKAN 'Str' DI-IMPORT

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Nonaktifkan Cek Foreign Key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan semua tabel (TRUNCATE)
        DB::table('article_tag')->truncate(); // Pivot table
        DB::table('media')->truncate();      // Hapus media
        DB::table('views')->truncate();     // Hapus views
        Article::truncate();
        Category::truncate();
        Tag::truncate();
        User::truncate(); // Sekarang aman untuk truncate users

        // 3. Aktifkan kembali Cek Foreign Key
        Schema::enableForeignKeyConstraints();

        // ----- 4. Buat User Penting (Admin & Editor) -----
        $admin = User::factory()->admin()->create([
            'name' => 'Admin LionNews',
            'email' => 'admin@lionnews.com',
        ]);
        
        $editor = User::factory()->editor()->create([
            'name' => 'Editor LionNews',
            'email' => 'editor@lionnews.com',
        ]);

        // ----- 5. Buat Jurnalis Lain -----
        $journalists = User::factory(10)->create(); // Buat 10 jurnalis

        // ----- 6. Buat Kategori & Tag DUMMY -----
        $categories = Category::factory(5)->create(); // Buat 5 Kategori dummy
        $tags = Tag::factory(15)->create(); // Buat 15 Tag dummy

        // ----- 7. Buat Artikel DUMMY -----
        
        // Buat 30 artikel 'published'
        Article::factory(30)->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ])->each(function ($article) use ($tags) {
            // Lampirkan 1-3 tag dummy acak
            $article->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
        
        // Buat 5 artikel 'draft'
        Article::factory(5)->draft()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);
        
        // Buat 3 artikel 'pending' (untuk demo dashboard)
        Article::factory(3)->pending()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        // ==========================================================
        // ----- 8. TAMBAHKAN 10 ARTIKEL DEMO SUMBA (REALISTIS) -----
        // ==========================================================

        // Ambil kategori 'Nasional' atau 'Ekonomi' (jika tidak ada, ambil acak)
        $nasionalCategory = Category::where('slug', 'nasional')->first() ?? $categories->first();
        $ekonomiCategory = Category::where('slug', 'ekonomi')->first() ?? $categories->last();

        // Daftar 10 Judul Berita Sumba
        $sumbaTitles = [
            1 => ['title' => 'Festival Pasola Berdarah: Tradisi vs. Keamanan Modern di Sumba Barat', 'tags' => ['Berita Sumba', 'Pasola', 'Sumba Barat', 'Budaya']],
            2 => ['title' => 'Kekeringan Melanda Sumba Timur, Petani Terancam Gagal Panen Jagung', 'tags' => ['Berita Sumba', 'Sumba Timur', 'Kekeringan']],
            3 => ['title' => 'Tenun Ikat Sumba Mendunia: Perajin Lokal Tembus Pasar Eropa', 'tags' => ['Ekonomi', 'Tenun Ikat', 'Berita Sumba']],
            4 => ['title' => 'Pembangunan Resor di Sumba Barat Daya Picu Perdebatan Lahan Adat', 'tags' => ['Berita Sumba', 'Sumba Barat Daya', 'Pariwisata']],
            5 => ['title' => 'Jalan Trans-Sumba Rusak Parah di Anakalang, Logistik Terhambat', 'tags' => ['Infrastruktur', 'Sumba Tengah', 'Berita Sumba']],
            6 => ['title' => 'Potensi Ekowisata Danau Weekuri: Surga Tersembunyi SBD Butuh Pengelolaan Serius', 'tags' => ['Pariwisata NTT', 'Sumba Barat Daya']],
            7 => ['title' => 'Polres Sumba Barat Tangkap Komplotan Pencuri Ternak yang Resahkan Warga', 'tags' => ['Kriminal', 'Pencurian Ternak', 'Sumba Barat']],
            8 => ['title' => 'Proyek \'Sumba Iconic Island\' Dievaluasi, Apa Kabar Kedaulatan Energi Terbarukan?', 'tags' => ['Energi Terbarukan', 'Sumba Iconic Island', 'NTT']],
            9 => ['title' => 'Wabah Malaria Kembali Muncul di Sumba Tengah, Dinkes Lakukan Fogging Massal', 'tags' => ['Kesehatan', 'Malaria', 'Sumba Tengah']],
            10 => ['title' => 'Kuda Sandelwood, Ikon Sumba yang Mulai Terlupakan Akibat Modernisasi', 'tags' => ['Budaya', 'Berita Sumba', 'Kuda Sandelwood']],
        ];

        // Buat Tag Sumba (jika belum ada)
        $allSumbaTags = collect($sumbaTitles)->flatMap(fn($item) => $item['tags'])->unique();
        $tagModels = collect();
        foreach ($allSumbaTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagModels->put($tagName, $tag->id); // Simpan ID-nya
        }

        // Buat 10 Artikel Sumba menggunakan Factory (untuk body/excerpt)
        foreach ($sumbaTitles as $item) {
            $article = Article::factory()->create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'user_id' => $admin->id, // Diposting oleh Admin
                'category_id' => ($item['tags'][0] == 'Ekonomi') ? $ekonomiCategory->id : $nasionalCategory->id, // Kategori yang relevan
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now()->subDays(rand(1, 15)), // Terbit dalam 15 hari terakhir
            ]);

            // Lampirkan Tag Sumba (sistem manual)
            $tagIdsToAttach = collect($item['tags'])->map(fn($tagName) => $tagModels->get($tagName));
            $article->tags()->sync($tagIdsToAttach);
        }
    }
}