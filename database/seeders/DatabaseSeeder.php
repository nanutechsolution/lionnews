<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
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
        $categoryData = [
            ['name' => 'Nasional', 'description' => 'Berita politik, kebijakan pemerintah, dan isu nasional.', 'is_featured' => 1, 'nav_order' => 1],
            ['name' => 'Ekonomi', 'description' => 'Bisnis, pasar, investasi, UMKM, dan ekonomi lokal/nasional.', 'is_featured' => 1, 'nav_order' => 2],
            ['name' => 'Sumba', 'description' => 'Berita khusus Sumba, budaya, pariwisata, dan sosial lokal.', 'is_featured' => 0, 'nav_order' => 3],
            ['name' => 'Budaya & Tradisi', 'description' => 'Adat, seni, festival, dan budaya lokal.', 'is_featured' => 0, 'nav_order' => 4],
            ['name' => 'Pariwisata & Travel', 'description' => 'Destinasi wisata, tips traveling, dan atraksi lokal.', 'is_featured' => 0, 'nav_order' => 5],
            ['name' => 'Kriminal & Hukum', 'description' => 'Kasus hukum, kriminalitas, dan berita kepolisian.', 'is_featured' => 0, 'nav_order' => 6],
            ['name' => 'Kesehatan', 'description' => 'Berita kesehatan, penyakit, rumah sakit, dan tips hidup sehat.', 'is_featured' => 0, 'nav_order' => 7],
            ['name' => 'Infrastruktur & Transportasi', 'description' => 'Pembangunan jalan, bandara, pelabuhan, dan transportasi umum.', 'is_featured' => 0, 'nav_order' => 8],
            ['name' => 'Energi & Lingkungan', 'description' => 'Energi terbarukan, lingkungan, dan bencana alam.', 'is_featured' => 0, 'nav_order' => 9],
            ['name' => 'Teknologi & Sains', 'description' => 'Inovasi, gadget, penelitian, dan sains populer.', 'is_featured' => 0, 'nav_order' => 10],
            ['name' => 'Olahraga', 'description' => 'Berita olahraga lokal dan nasional, atlet, dan turnamen.', 'is_featured' => 0, 'nav_order' => 11],
            ['name' => 'Opini & Editorial', 'description' => 'Analisis, komentar, dan kolom opini.', 'is_featured' => 0, 'nav_order' => 12],
        ];

        $categories = collect();
        foreach ($categoryData as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_featured' => $cat['is_featured'],
                'nav_order' => $cat['nav_order'],
            ]);
            $categories->put($cat['name'], $category);
        }

        // ----- 7. Buat Tag Dummy -----
        $tags = Tag::factory(15)->create();

        // ----- 8. Buat Artikel Dummy -----
        Article::factory(30)->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ])->each(function ($article) use ($tags) {
            $article->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });

        Article::factory(5)->draft()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);

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

        $allSumbaTags = collect($sumbaTitles)->flatMap(fn($item) => $item['tags'])->unique();
        $tagModels = collect();
        foreach ($allSumbaTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagModels->put($tagName, $tag->id);
        }

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
