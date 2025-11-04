<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),

    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            // GANTI 'title' dari "It's Over 9000!" menjadi nama brand Anda
            'title' => 'LionNews', // <-- GANTI
            
            'titleBefore' => false, 
            
            // GANTI deskripsi default
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya seputar politik, ekonomi, olahraga, dan teknologi.', // <-- GANTI
            
            // GANTI separator dari ' - ' menjadi ' | '
            'separator' => ' | ', // <-- GANTI
            
            'keywords' => [],
            
            // GANTI canonical menjadi 'current' agar URL saat ini digunakan
            'canonical' => 'current', // <-- GANTI
            
            // GANTI robots agar Google tahu situs ini boleh di-index
            'robots' => 'all', // <-- GANTI
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            // GANTI title
            'title' => 'LionNews', // <-- GANTI
            
            // GANTI deskripsi
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya seputar politik, ekonomi, olahraga, dan teknologi.', // <-- GANTI
            
            // GANTI url menjadi null (agar otomatis diisi)
            'url' => null, // <-- GANTI
            
            'type' => 'WebPage', // GANTI dari false
            
            // GANTI site_name
            'site_name' => 'LionNews', // <-- GANTI
            
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            // TAMBAHKAN: 'summary_large_image' adalah terbaik untuk berita
            'card' => 'summary_large_image', // <-- TAMBAHKAN
            
            // TAMBAHKAN: Ganti dengan username Twitter Anda
            'site' => '@LionNews', // <-- TAMBAHKAN
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            // GANTI title
            'title' => 'LionNews', // <-- GANTI
            
            // GANTI deskripsi
            'description' => 'LionNews - Menyajikan berita terkini, mendalam, dan terpercaya seputar politik, ekonomi, olahraga, dan teknologi.', // <-- GANTI
            
            // GANTI url menjadi null (agar otomatis diisi)
            'url' => null, // <-- GANTI
            
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];