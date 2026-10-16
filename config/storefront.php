<?php

/**
 * Data sementara (dummy) storefront. Ganti dengan data database
 * saat model Category dan Product sudah tersedia.
 */
return [
    'categories' => [
        ['name' => 'Smartphone', 'slug' => 'smartphone'],
        ['name' => 'Laptop', 'slug' => 'laptop'],
        ['name' => 'Audio & Headphone', 'slug' => 'audio'],
        ['name' => 'Kamera', 'slug' => 'kamera'],
        ['name' => 'Smartwatch', 'slug' => 'smartwatch'],
        ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
    ],

    'slides' => [
        [
            'image' => 'https://picsum.photos/seed/snstore-hero1/1600/700',
            'badge' => 'Promo Akhir Tahun',
            'title' => 'Teknologi Terbaru, Harga Terbaik',
            'subtitle' => 'Diskon hingga 40% untuk smartphone dan laptop pilihan.',
        ],
        [
            'image' => 'https://picsum.photos/seed/snstore-hero2/1600/700',
            'badge' => 'Produk Baru',
            'title' => 'Upgrade Audio Anda Hari Ini',
            'subtitle' => 'Headphone dan speaker premium dengan garansi resmi.',
        ],
        [
            'image' => 'https://picsum.photos/seed/snstore-hero3/1600/700',
            'badge' => 'Gratis Ongkir',
            'title' => 'Belanja Elektronik Tanpa Ribet',
            'subtitle' => 'Pengiriman cepat ke seluruh Indonesia.',
        ],
    ],
];
