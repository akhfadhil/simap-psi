# SIMAP Partai Template

SIMAP Partai Template adalah fondasi generik lengkap untuk membuat aplikasi rekap dan saksi satu partai berdasarkan hasil hardening SIMAP Garuda.

Template ini telah berisi aplikasi Laravel lengkap (100% siap pakai) yang siap diturunkan menjadi project partai baru dengan modifikasi konfigurasi minimal.

## Tujuan

- Menjadi blueprint untuk project seperti `simap-golkar`, `simap-pkb`, atau `simap-gerindra`.
- Memakai model satu aplikasi untuk satu partai.
- Menjaga identitas partai di `config/party.php`, bukan hardcode di controller/view.
- Menstandarkan role `admin_partai`, `korcam`, `kordes`, dan `saksi_tps`.
- Menstandarkan scope wilayah kecamatan, desa, dan TPS.

## Fitur Utama & Struktur Project

- **Skeleton Laravel**: Konfigurasi framework Laravel standar yang siap jalan.
- **Konfigurasi Dinamis**: `config/party.php` menjadi satu-satunya tempat untuk merubah identitas, warna aksen UI, logo, dan nama partai.
- **Role & Scope Wilayah**: Akses login terproteksi untuk Admin Partai, Korcam, Kordes, dan Saksi TPS dengan pembatasan hak akses berbasis wilayah.
- **Input Manual TPS**: Alur pengisian rekap suara legislatif (DPR RI, DPRD Prov, DPRD Kab) dari Saksi TPS dengan asisten input Kordes/Korcam/Admin.
- **Dashboard & Agregasi**: Grafik sebaran suara interaktif, rangkuman statistik real-time, wilayah kuat/lemah, status finalisasi, dan TPS perlu dicek.
- **Export Laporan**: Ekspor rekapitulasi data per wilayah ke Excel, ekspor TPS belum masuk, dan ekspor TPS bermasalah.
- **Demo Data Seeder**: Command `php artisan db:seed --class=PartyDemoSeeder` untuk mempopulasikan data uji tiruan secara otomatis untuk demonstrasi.
- **Automated Test Suite**: Dilengkapi dengan unit & feature testing penuh (`php artisan test`) untuk menjamin kestabilan kode.

## Cara Menggunakan Template
1. Clone repositori template ke folder project partai baru (misal: `simap-golkar`).
2. Jalankan setup awal: `composer install` & `npm install`.
3. Buat file `.env` (bisa meng-copy dari `.env.example`) dan sesuaikan kredensial database.
4. Sesuaikan konfigurasi identitas partai di [config/party.php](file:///c:/laragon/www/simap-partai-template/config/party.php).
5. Letakkan logo partai pada folder `public/images/` dan sesuaikan jalurnya di konfigurasi.
6. Jalankan migrasi schema: `php artisan migrate`.
7. (Opsional) Jalankan demo data seeder untuk pengujian awal: `php artisan db:seed --class=PartyDemoSeeder`.
8. Verifikasi dengan menjalankan test suite: `php artisan test` dan jalankan server pengembangan: `npm run dev`.

## Panduan Proyek Baru & Operasional Lengkap
*   **Pembuatan Proyek Partai Lain**: Langkah detail cara menurunkan template ini menjadi proyek partai baru dapat dilihat pada berkas [HOW_TO_GENERATE_PARTY.md](file:///c:/laragon/www/simap-partai-template/HOW_TO_GENERATE_PARTY.md).
*   **Operasional & Deployment**: Detail operasional deployment, konfigurasi, backup, dan troubleshooting dapat dilihat pada berkas [PARTY_PROJECT_OPERASIONAL.md](file:///c:/laragon/www/simap-partai-template/PARTY_PROJECT_OPERASIONAL.md).
