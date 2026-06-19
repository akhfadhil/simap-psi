<?php

return [
    'slug' => env('PARTY_SLUG', 'party-slug'),
    'name' => env('PARTY_NAME', 'Nama Partai'),
    'short_name' => env('PARTY_SHORT_NAME', 'Partai'),
    'app_name' => env('APP_NAME', 'SIMAP Partai'),
    'tagline' => env('PARTY_TAGLINE', 'Sistem Rekap dan Saksi Partai'),
    'active_year' => (int) env('PARTY_ACTIVE_YEAR', 2026),
    'copyright_year' => (int) env('PARTY_COPYRIGHT_YEAR', 2026),

    'historical_numbers' => [
        2024 => (int) env('PARTY_NUMBER_2024', 0),
    ],

    'election_types' => [
        'dpr_ri',
        'dprd_prov',
        'dprd_kab',
    ],

    'assets' => [
        'logo' => env('PARTY_LOGO_PATH', 'images/party-logo.png'),
    ],

    'colors' => [
        'primary' => env('PARTY_COLOR_PRIMARY', '#1D4ED8'),
        'primary_dark' => env('PARTY_COLOR_PRIMARY_DARK', '#1E40AF'),
        'primary_soft' => env('PARTY_COLOR_PRIMARY_SOFT', 'rgba(29, 78, 216, .1)'),
        'korcam' => env('PARTY_COLOR_KORCAM', '#F59E0B'),
        'kordes' => env('PARTY_COLOR_KORDES', '#14B8A6'),
        'saksi_tps' => env('PARTY_COLOR_SAKSI_TPS', '#38BDF8'),
    ],

    'roles' => [
        'admin_partai' => 'Admin Partai',
        'korcam' => 'Korcam',
        'kordes' => 'Kordes',
        'saksi_tps' => 'Saksi TPS',
    ],
];
