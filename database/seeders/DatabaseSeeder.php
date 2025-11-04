<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // <-- Import Schema
use Illuminate\Support\Facades\DB;     // <-- Import DB (untuk pivot table)

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
        // Sebaiknya kosongkan tabel 'anak' dulu
        DB::table('article_tag')->truncate(); // Pivot table
        Article::truncate();
        Category::truncate();
        Tag::truncate();
        User::truncate(); // Sekarang aman untuk truncate users

        // 3. Aktifkan kembali Cek Foreign Key
        Schema::enableForeignKeyConstraints();

        // ----- 4. Buat User Penting (Admin & Editor) -----
        $admin = User::factory()->admin()->create([
            'name' => 'Admin LionNews',
            'email' => 'admin@lionnews.test',
        ]);
        
        $editor = User::factory()->editor()->create([
            'name' => 'Editor LionNews',
            'email' => 'editor@lionnews.test',
        ]);

        // ----- 5. Buat Jurnalis Lain -----
        $journalists = User::factory(10)->create(); // Buat 10 jurnalis

        // ----- 6. Buat Kategori & Tag -----
        $categories = Category::factory(5)->create(); // Buat 5 Kategori
        $tags = Tag::factory(15)->create(); // Buat 15 Tag

        // ----- 7. Buat Artikel -----
        
        // Buat 30 artikel 'published'
        Article::factory(30)->create([
            // Pilih user & kategori secara acak dari yang baru dibuat
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ])->each(function ($article) use ($tags) {
            // Lampirkan 1-3 tag acak ke setiap artikel
            $article->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
        
        // Buat 5 artikel 'draft'
        Article::factory(5)->draft()->create([
            'user_id' => $journalists->random()->id,
            'category_id' => $categories->random()->id,
        ]);
    }
}