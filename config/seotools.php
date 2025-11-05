<?php
return [
    'meta' => [
        'defaults' => [
            'title' => 'LionNews', // Judul default Anda
            'titleBefore' => false,
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya.', // Deskripsi default Anda
            'separator' => ' | ', // Ganti ' - ' menjadi ' | '
            'keywords' => [],
            'canonical' => 'current', // Gunakan URL saat ini
            'robots' => 'all',     // Izinkan Google meng-index
        ],
    ],
    'opengraph' => [
        'defaults' => [
            'title' => 'LionNews', // Judul OG default
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya.', // Deskripsi OG default
            'url' => null, // Biarkan null agar diisi otomatis
            'type' => 'WebPage',
            'site_name' => 'LionNews', // Nama situs Anda
            'images' => [], // Gambar default (jika ada)
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image', // Terbaik untuk berita
            // 'site'        => '@LionNews', // Ganti dengan handle Twitter Anda
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title' => 'LionNews', // Judul JSON-LD default
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya.', // Deskripsi JSON-LD default
            'url' => null, // Biarkan null
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];