<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Nonaktifkan cek foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan semua tabel
        DB::table('article_tag')->truncate();
        DB::table('media')->truncate();
        DB::table('views')->truncate();
        Article::truncate();
        Category::truncate();
        Tag::truncate();
        User::truncate();

        // 3. Aktifkan kembali cek foreign key
        Schema::enableForeignKeyConstraints();

        // ----- 4. Buat User Penting -----
        $admin = User::factory()->admin()->create([
            'name' => 'Admin LionNews',
            'email' => 'admin@lionnews.com',
        ]);
        $editor = User::factory()->editor()->create([
            'name' => 'Editor LionNews',
            'email' => 'editor@lionnews.com',
        ]);

        // ----- 5. Buat Jurnalis Lain -----
        $journalists = User::factory(10)->create();

        // ----- 6. Buat Kategori LionNews -----
        $categoryNames = [
            'Nasional',
            'Ekonomi',
            'Sumba',
            'Budaya & Tradisi',
            'Pariwisata & Travel',
            'Kriminal & Hukum',
            'Kesehatan',
            'Infrastruktur & Transportasi',
            'Energi & Lingkungan',
            'Teknologi & Sains',
            'Olahraga',
            'Opini & Editorial',
        ];

        $categories = collect();
        foreach ($categoryNames as $name) {
            $category = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
            $categories->put($name, $category);
        }

        // ----- 7. Buat Tag Dummy -----
        $tags = Tag::factory(15)->create();

        // ----- 8. Buat Artikel Dummy -----
        // 30 artikel published
        Article::factory(30)->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ])->each(function ($article) use ($tags) {
            $article->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });

        // 5 draft
        Article::factory(5)->draft()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        // 3 pending
        Article::factory(3)->pending()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        // ----- 9. Artikel Demo Sumba -----
        $sumbaTitles = [
            ['title' => 'Festival Pasola Berdarah: Tradisi vs. Keamanan Modern di Sumba Barat', 'tags' => ['Berita Sumba','Pasola','Sumba Barat','Budaya']],
            ['title' => 'Kekeringan Melanda Sumba Timur, Petani Terancam Gagal Panen Jagung', 'tags' => ['Berita Sumba','Sumba Timur','Kekeringan']],
            ['title' => 'Tenun Ikat Sumba Mendunia: Perajin Lokal Tembus Pasar Eropa', 'tags' => ['Ekonomi','Tenun Ikat','Berita Sumba']],
            ['title' => 'Pembangunan Resor di Sumba Barat Daya Picu Perdebatan Lahan Adat', 'tags' => ['Berita Sumba','Sumba Barat Daya','Pariwisata']],
            ['title' => 'Jalan Trans-Sumba Rusak Parah di Anakalang, Logistik Terhambat', 'tags' => ['Infrastruktur','Sumba Tengah','Berita Sumba']],
            ['title' => 'Potensi Ekowisata Danau Weekuri: Surga Tersembunyi SBD Butuh Pengelolaan Serius', 'tags' => ['Pariwisata NTT','Sumba Barat Daya']],
            ['title' => 'Polres Sumba Barat Tangkap Komplotan Pencuri Ternak yang Resahkan Warga', 'tags' => ['Kriminal','Pencurian Ternak','Sumba Barat']],
            ['title' => "Proyek 'Sumba Iconic Island' Dievaluasi, Apa Kabar Kedaulatan Energi Terbarukan?", 'tags' => ['Energi Terbarukan','Sumba Iconic Island','NTT']],
            ['title' => 'Wabah Malaria Kembali Muncul di Sumba Tengah, Dinkes Lakukan Fogging Massal', 'tags' => ['Kesehatan','Malaria','Sumba Tengah']],
            ['title' => 'Kuda Sandelwood, Ikon Sumba yang Mulai Terlupakan Akibat Modernisasi', 'tags' => ['Budaya','Berita Sumba','Kuda Sandelwood']],
        ];

        // Buat Tag Sumba jika belum ada
        $allSumbaTags = collect($sumbaTitles)->flatMap(fn($item) => $item['tags'])->unique();
        $tagModels = collect();
        foreach ($allSumbaTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagModels->put($tagName, $tag->id);
        }

        // Buat Artikel Sumba
        foreach ($sumbaTitles as $item) {
            $categoryId = ($item['tags'][0] == 'Ekonomi') ? $categories->get('Ekonomi')->id : $categories->get('Sumba')->id;

            $article = Article::factory()->create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'user_id' => $admin->id,
                'category_id' => $categoryId,
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now()->subDays(rand(1, 15)),
            ]);

            $tagIdsToAttach = collect($item['tags'])->map(fn($tagName) => $tagModels->get($tagName));
            $article->tags()->sync($tagIdsToAttach);
        }
    }
}
