<?php

return [
    'slug' => env('PARTY_SLUG', 'partai'),
    'name' => env('PARTY_NAME', 'Nama Partai'),
    'short_name' => env('PARTY_SHORT_NAME', 'Partai'),
    'app_name' => env('PARTY_APP_NAME', env('APP_NAME', 'SIMAP Partai')),
    'tagline' => env('PARTY_TAGLINE', 'Sistem Rekap dan Saksi'),
    'active_year' => (int) env('PARTY_ACTIVE_YEAR', date('Y')),
    'copyright_year' => (int) env('PARTY_COPYRIGHT_YEAR', date('Y')),

    'historical_numbers' => env('PARTY_HISTORICAL_NUMBERS') ? json_decode(env('PARTY_HISTORICAL_NUMBERS'), true) : [2024 => 11],

    'election_types' => [
        'dpr_ri',
        'dprd_prov',
        'dprd_kab',
    ],

    'assets' => [
        'logo' => env('PARTY_LOGO', 'images/party-logo.png'),
    ],

    'colors' => [
        'primary' => env('PARTY_COLOR_PRIMARY', '#3B82F6'),
        'primary_dark' => env('PARTY_COLOR_PRIMARY_DARK', '#1D4ED8'),
        'primary_soft' => env('PARTY_COLOR_PRIMARY_SOFT', 'rgba(59, 130, 246, .1)'),
        'korcam' => '#F4A261',
        'kordes' => '#2EC4B6',
        'saksi_tps' => '#7DD3FC',
    ],

    'roles' => [
        'admin_partai' => 'Admin Partai',
        'korcam' => 'Korcam',
        'kordes' => 'Kordes',
        'saksi_tps' => 'Saksi TPS',
    ],

    'main_simap_url' => env('MAIN_SIMAP_URL', 'http://simap.test'),
];
