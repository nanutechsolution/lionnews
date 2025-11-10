<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Page;  // <-- 1. IMPORT MODEL PAGE
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for production or fresh install.
     */
    public function run(): void
    {
        // 1. Nonaktifkan cek foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan semua tabel
        DB::table('article_tag')->truncate();
        DB::table('media')->truncate();
        DB::table('views')->truncate();
        DB::table('comments')->truncate(); // <-- Hapus komentar
        Article::truncate();               // <-- Hapus artikel
        Page::truncate();                  // <-- Hapus halaman
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

        // ----- 5. Buat 10 Akun Jurnalis Kosong -----
        $journalists = User::factory(10)->create();

        // ----- 6. Buat Kategori Profesional LionNews -----
        $categoryData = [
            ['name' => 'Nasional', 'description' => 'Berita politik, kebijakan pemerintah, dan isu nasional.', 'is_featured' => 1, 'nav_order' => 1],
            ['name' => 'Ekonomi', 'description' => 'Bisnis, pasar, investasi, UMKM, dan ekonomi lokal/nasional.', 'is_featured' => 1, 'nav_order' => 2],
            ['name' => 'Sumba', 'description' => 'Berita khusus Sumba, budaya, pariwisata, dan sosial lokal.', 'is_featured' => 1, 'nav_order' => 3, 'parent_id' => null], // <-- Kategori Induk Sumba
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
                'parent_id' => $cat['parent_id'] ?? null, // Tambahkan parent_id
            ]);
            $categories->put($cat['name'], $category);
        }

        // Buat Sub-kategori Sumba
        $sumbaCategory = $categories->get('Sumba');
        if ($sumbaCategory) {
            $subCategories = ['Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur'];
            foreach ($subCategories as $subName) {
                Category::create([
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'description' => 'Berita dari ' . $subName,
                    'is_featured' => 0, // Tidak tampil di nav utama
                    'parent_id' => $sumbaCategory->id, // Set Sumba sebagai Induk
                ]);
            }
        }

        // ----- 7. Buat Daftar Tagar (Tags) Awal -----
        $tagNames = [
            'Berita Sumba','Pasola','Budaya','Kekeringan','Ekonomi','Tenun Ikat',
            'UMKM','Pariwisata','Konflik Lahan','Infrastruktur','Pariwisata NTT',
            'Kriminal','Pencurian Ternak','Energi Terbarukan','Sumba Iconic Island',
            'NTT','Kesehatan','Malaria','Kuda Sandelwood'
        ];
        foreach ($tagNames as $tagName) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
        }

        // ----- 8. Buat Halaman (Pages) Esensial -----
        $this->seedPages();
    }

    /**
     * Helper function untuk membuat Halaman (Pages)
     */
    private function seedPages(): void
    {
        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => '<p><strong>LionNews</strong> adalah portal berita independen yang lahir di jantung Nusa Tenggara Timur, dengan visi untuk menjadi "Raja Hutan" informasi yang berwibawa di kancah digital.</p><p>Kami percaya pada jurnalisme yang didasari oleh dua pilar utama, seperti yang tercermin pada warna kami:</p><ul><li><strong>Biru (Deep Blue):</strong> Melambangkan <strong>Kepercayaan (Trust)</strong>, stabilitas, dan kredibilitas. Berita kami disajikan secara mendalam, akurat, dan dapat dipertanggungjawabkan.</li><li><strong>Emas (Gold):</strong> Melambangkan <strong>Prestise (Prestige)</strong> dan kekuasaan. Kami tidak hanya melaporkan, tapi menyajikan analisis kritis yang berkelas dan berdampak.</li></ul><p>Di era banjir informasi, LionNews hadir sebagai filter Anda—menyajikan berita yang penting, berani, dan elegan.</p>',
                'is_published' => true
            ],
            [
                'title' => 'Tim Redaksi',
                'slug' => 'redaksi',
                'body' => '<h2>Dewan Redaksi</h2><p>Penanggung Jawab / Pemimpin Redaksi:</p><p><strong>[Nama Anda Di Sini]</strong></p><p>Wakil Pemimpin Redaksi:</p><p><strong>[Nama Editor Senior]</strong></p><br><h2>Tim Liputan</h2><p>Editor Pelaksana:</p><p><strong>[Nama Editor 1]</strong></p><p><strong>[Nama Editor 2]</strong></p><br><p>Jurnalis Senior:</p><p><strong>[Nama Jurnalis 1]</strong></p><p><strong>[Nama Jurnalis 2]</strong></p><br><h2>Kontak Redaksi</h2><p>Email: redaksi@lionnews.test</p><p>Alamat: [Alamat Kantor Anda]</p>',
                'is_published' => true
            ],
            [
                'title' => 'Pedoman Media Siber',
                'slug' => 'pedoman-media-siber',
                'body' => '<p>Kemerdekaan berpendapat, kemerdekaan berekspresi, dan kemerdekaan pers adalah hak asasi manusia yang dilindungi Pancasila, Undang-Undang Dasar 1945, dan Deklarasi Universal Hak Asasi Manusia PBB...</p><h2>1. Ruang Lingkup</h2><p>a. Media Siber adalah segala bentuk media yang menggunakan wahana internet dan melaksanakan kegiatan jurnalistik...</p><p><em>(Silakan salin-tempel teks lengkap dari situs Dewan Pers)</em></p>',
                'is_published' => true
            ],
             [
                'title' => 'Kontak Kami',
                'slug' => 'kontak-kami',
                'body' => '<h2>Kontak Redaksi & Iklan</h2><p>Untuk kerja sama liputan, undangan pers, atau pemasangan iklan, silakan hubungi kami di:</p><p>Email: <strong>redaksi@lionnews.test</strong></p><p>Telepon: <strong>[Nomor Telepon Anda]</strong></p><p>Alamat: <strong>[Alamat Kantor Anda]</strong></p>',
                'is_published' => true
            ]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
