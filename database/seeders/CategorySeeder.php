<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nasional',
                'description' => 'Berita politik, kebijakan pemerintah, dan isu nasional.',
                'is_featured' => 1,
                'nav_order' => 1,
            ],
            [
                'name' => 'Ekonomi',
                'description' => 'Bisnis, pasar, investasi, UMKM, dan ekonomi lokal/nasional.',
                'is_featured' => 1,
                'nav_order' => 2,
            ],
            [
                'name' => 'Sumba',
                'description' => 'Berita khusus Sumba, budaya, pariwisata, dan sosial lokal.',
                'is_featured' => 0,
                'nav_order' => 3,
            ],
            [
                'name' => 'Budaya & Tradisi',
                'description' => 'Adat, seni, festival, dan budaya lokal.',
                'is_featured' => 0,
                'nav_order' => 4,
            ],
            [
                'name' => 'Pariwisata & Travel',
                'description' => 'Destinasi wisata, tips traveling, dan atraksi lokal.',
                'is_featured' => 0,
                'nav_order' => 5,
            ],
            [
                'name' => 'Kriminal & Hukum',
                'description' => 'Kasus hukum, kriminalitas, dan berita kepolisian.',
                'is_featured' => 0,
                'nav_order' => 6,
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Berita kesehatan, penyakit, rumah sakit, dan tips hidup sehat.',
                'is_featured' => 0,
                'nav_order' => 7,
            ],
            [
                'name' => 'Infrastruktur & Transportasi',
                'description' => 'Pembangunan jalan, bandara, pelabuhan, dan transportasi umum.',
                'is_featured' => 0,
                'nav_order' => 8,
            ],
            [
                'name' => 'Energi & Lingkungan',
                'description' => 'Energi terbarukan, lingkungan, dan bencana alam.',
                'is_featured' => 0,
                'nav_order' => 9,
            ],
            [
                'name' => 'Teknologi & Sains',
                'description' => 'Inovasi, gadget, penelitian, dan sains populer.',
                'is_featured' => 0,
                'nav_order' => 10,
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Berita olahraga lokal dan nasional, atlet, dan turnamen.',
                'is_featured' => 0,
                'nav_order' => 11,
            ],
            [
                'name' => 'Opini & Editorial',
                'description' => 'Analisis, komentar, dan kolom opini.',
                'is_featured' => 0,
                'nav_order' => 12,
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                $cat
            );
        }
    }
}
