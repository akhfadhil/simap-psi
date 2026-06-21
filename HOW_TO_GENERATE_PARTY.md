# Panduan Pembuatan Proyek Partai Baru Dari Template

Dokumen ini menjelaskan langkah-langkah untuk membuat proyek aplikasi rekapitulasi mandiri untuk partai politik baru menggunakan **SIMAP Partai Template**. Proses ini melibatkan ekspor snapshot data dari aplikasi **SIMAP Utama** dan mengimpornya ke dalam salinan template ini.

---

## Prasyarat
1. Proyek **SIMAP Utama** terpasang dan memiliki database yang sudah terisi data wilayah (Dapil, Kecamatan, Desa, TPS) dan hasil perolehan suara.
2. Profil partai target sudah terdaftar di SIMAP Utama (dapat diperiksa melalui menu Setup di halaman Admin Utama).

---

## Langkah-Langkah Pembuatan Proyek Baru

### Langkah 1: Ekspor Snapshot dari SIMAP Utama
Jalankan perintah ekspor di root direktori proyek **SIMAP Utama** dengan menyertakan slug dari partai yang diinginkan (misal: `pkb`, `pdi-p`, atau `gerindra`):

```bash
php artisan export:party-snapshot {slug}
```

*Contoh*:
```bash
php artisan export:party-snapshot pkb
```

Perintah ini akan menghasilkan berkas ekspor JSON di folder:  
`storage/app/private/exports/party-snapshot-{slug}-{timestamp}.json`

---

### Langkah 2: Buat Salinan Template
1. Salin seluruh isi folder proyek `simap-partai-template` ke folder proyek baru Anda (misalnya: `simap-pkb` atau `simap-gerindra`).
2. Buka folder proyek baru tersebut menggunakan terminal atau editor kode pilihan Anda.

---

### Langkah 3: Setup Environment
1. Buat file konfigurasi `.env` baru dengan menduplikat file `.env.example`:
   ```bash
   copy .env.example .env
   ```
2. Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env` untuk proyek baru ini. Pastikan database baru tersebut sudah dibuat terlebih dahulu di MySQL/Laragon.

---

### Langkah 4: Impor Snapshot Data ke Proyek Baru
1. Salin file JSON hasil ekspor dari **Langkah 1** ke suatu tempat di PC Anda (atau biarkan di folder asalnya).
2. Jalankan perintah impor di root direktori proyek partai baru Anda dengan menyertakan jalur lengkap menuju file JSON snapshot tersebut:

```bash
php artisan import:party-snapshot "C:\path\to\exports\party-snapshot-pkb-xxxxxx.json"
```

Perintah ini akan melakukan hal berikut secara otomatis:
* Mengosongkan data wilayah dan tabel rekapitulasi legislatif lokal demi menghindari duplikasi.
* Mengimpor data Dapil, Kecamatan, Desa, dan TPS secara lengkap.
* Mengimpor data master partai target beserta caleg-calegnya.
* Mengimpor data header rekapitulasi serta suara partai & caleg yang sudah tersaring (aman dari data partai kompetitor).
* Memperbarui konfigurasi identitas partai (`PARTY_SLUG`, `PARTY_NAME`, `PARTY_SHORT_NAME`, warna visual utama/aksen, dan logo) langsung di file `.env` proyek baru Anda.

---

### Langkah 5: Salin Aset Visual
Jika partai memiliki logo khusus:
1. Pastikan logo tersebut berada di folder `public/images/` di proyek baru Anda.
2. Periksa kembali kecocokan jalur logo pada kunci `PARTY_LOGO` di file `.env` Anda.

---

### Langkah 6: Jalankan Pengujian
Verifikasi bahwa seluruh sistem telah berjalan dengan sukses dengan menjalankan suite pengujian otomatis:

```bash
php artisan test
```

Semua pengujian (unit & feature) harus berstatus **PASS**. Setelah terkonfirmasi, Anda dapat menyalakan server lokal:

```bash
npm run dev
```

Aplikasi rekapitulasi khusus partai Anda sekarang siap digunakan!