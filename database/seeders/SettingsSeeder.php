<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Jalankan seeder pengaturan situs.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1], // Cari baris dengan ID 1
            [
                'breaking_news_active' => true,
                'breaking_news_text' => 'BREAKING: Erupsi Gunung Lewotobi, Warga Diimbau Menjauh 5 KM',
                'breaking_news_link' => '/search?q=Erupsi'
            ]
        );
    }
}
