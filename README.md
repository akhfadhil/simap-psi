# SIMAP Partai Template

SIMAP Partai Template adalah fondasi generik untuk membuat aplikasi rekap dan saksi satu partai berdasarkan hasil hardening SIMAP Garuda.

Template ini belum berisi aplikasi Laravel lengkap. Tahap awal ini hanya mengunci struktur konfigurasi partai, helper identitas partai, service scope wilayah, dan dokumentasi ekstraksi agar project partai berikutnya tidak dimulai dari copy mentah.

## Tujuan

- Menjadi blueprint untuk project seperti `simap-golkar`, `simap-pkb`, atau `simap-gerindra`.
- Memakai model satu aplikasi untuk satu partai.
- Menjaga identitas partai di `config/party.php`, bukan hardcode di controller/view.
- Menstandarkan role `admin_partai`, `korcam`, `kordes`, dan `saksi_tps`.
- Menstandarkan scope wilayah kecamatan, desa, dan TPS.

## Isi Tahap Pertama

```text
config/party.php
app/Support/PartyConfig.php
app/Services/PartyScopeService.php
app/Models/
database/migrations/0001_01_01_000000_create_party_app_schema.php
PARTY_PROJECT_OPERASIONAL.md
```

## Belum Masuk

- Skeleton Laravel lengkap.
- Controller, route, Blade, export, dashboard, dan test.
- Factories dan seeders.
- Import snapshot dari SIMAP utama.

## Prinsip Ekstraksi

- Jangan membawa hardcode Garuda.
- Jangan membawa backward route legacy `ppk`, `pps`, dan `kpps`.
- Jangan membawa modul dokumen/verifikasi KPU.
- Jangan membawa rekap non-legislatif PPWP, DPD, Gubernur, atau Bupati.
- Jangan membawa migration cleanup/histori fork sebagai migration template fresh.
