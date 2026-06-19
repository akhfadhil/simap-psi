# Operasional Template Project Partai

Dokumen ini menjadi catatan awal untuk menurunkan SIMAP Partai Template menjadi project partai baru.

## Status Template

Tahap pertama baru mengekstrak fondasi kecil dari SIMAP Garuda:

- konfigurasi identitas partai,
- helper pencocokan partai,
- service scope wilayah,
- migration fresh/squashed awal untuk schema inti partai,
- prinsip role final,
- batasan fitur yang tidak boleh ikut template.

Template belum siap dipakai sebagai aplikasi produksi sampai skeleton Laravel lengkap, route, controller, view, export, dan test dipindahkan secara bertahap.

## Role Standar

| Role DB | Nama UI | Scope |
| --- | --- | --- |
| `admin_partai` | Admin Partai | Semua wilayah |
| `korcam` | Korcam | Satu kecamatan |
| `kordes` | Kordes | Satu desa |
| `saksi_tps` | Saksi TPS | Satu TPS |

## Konfigurasi Identitas

Setiap project partai wajib mengganti nilai di `config/party.php`:

- `slug`
- `name`
- `short_name`
- `app_name`
- `tagline`
- `historical_numbers`
- `assets.logo`
- `colors`

Nomor urut partai hanya metadata historis per pemilu, bukan identitas permanen.

## Langkah Berikutnya

1. Copy skeleton Laravel dari SIMAP Garuda yang sudah dibersihkan.
2. Review migration fresh/squashed terhadap kode runtime saat model mulai dipindahkan.
3. Pindahkan model, controller, route, view, export, service, dan test secara bertahap.
4. Hapus backward route legacy dari calon template.
5. Pastikan semua label partai memakai `config('party.*')` atau `PartyConfig`.
6. Jalankan test setelah setiap batch kecil.

## Schema Fresh Awal

Migration awal berada di:

```text
database/migrations/0001_01_01_000000_create_party_app_schema.php
```

Schema ini sengaja hanya membawa:

- core auth/session/cache/jobs Laravel,
- wilayah dan dapil,
- user role final partai,
- pemilu setting legislatif,
- master partai/caleg,
- rekap TPS legislatif,
- suara partai/caleg,
- flag internal.

Schema ini tidak membawa tabel dokumen, tabel non-legislatif, role legacy, atau migration cleanup dari SIMAP Garuda.
