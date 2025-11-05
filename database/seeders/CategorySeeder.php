<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
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

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
