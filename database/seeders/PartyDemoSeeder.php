<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dapil;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Tps;
use App\Models\User;
use App\Models\RekapPartai;
use App\Models\RekapCaleg;
use App\Models\RekapHeader;
use App\Models\RekapPartaiSuara;
use App\Models\RekapCalegSuara;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PartyDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $party = config('party');

        // 1. Buat Dapil Kabupaten
        $dapil1 = Dapil::firstOrCreate(['nama' => 'Dapil A']);
        $dapil2 = Dapil::firstOrCreate(['nama' => 'Dapil B']);

        // 2. Kaitkan Kecamatan ke Dapil
        $kecDapil1 = ['Banyuwangi', 'Giri', 'Glagah', 'Kalipuro', 'Licin', 'Wongsorejo'];
        Kecamatan::whereIn('nama', $kecDapil1)->update(['dapil_id' => $dapil1->id]);

        $kecDapil2 = ['Rogojampi', 'Blimbingsari', 'Kabat', 'Singojuruh', 'Songgon'];
        Kecamatan::whereIn('nama', $kecDapil2)->update(['dapil_id' => $dapil2->id]);

        // Seed master partai (PartaiSeeder mendaftarkan 18 partai nasional)
        $this->call(PartaiSeeder::class);

        // 3. Buat Data TPS
        $tpsData = [
            'Banyuwangi' => [
                'Lateng' => ['TPS 01', 'TPS 02', 'TPS 03'],
                'Tamanbaru' => ['TPS 01', 'TPS 02'],
            ],
            'Giri' => [
                'Boyolangu' => ['TPS 01', 'TPS 02'],
            ],
            'Blimbingsari' => [
                'Badean' => ['TPS 01', 'TPS 02'],
            ],
            'Rogojampi' => [
                'Gitik' => ['TPS 01', 'TPS 02'],
            ]
        ];

        $createdTps = [];

        foreach ($tpsData as $kecNama => $desas) {
            $kec = Kecamatan::firstOrCreate(
                ['nama' => $kecNama],
                ['created_at' => $now, 'updated_at' => $now]
            );

            foreach ($desas as $desaNama => $tpsList) {
                $desa = Desa::firstOrCreate(
                    ['nama' => $desaNama, 'kecamatan_id' => $kec->id],
                    ['created_at' => $now, 'updated_at' => $now]
                );

                foreach ($tpsList as $tpsNama) {
                    $tps = Tps::firstOrCreate(
                        ['nama' => $tpsNama, 'desa_id' => $desa->id],
                        ['created_at' => $now, 'updated_at' => $now]
                    );
                    $createdTps["{$kecNama}_{$desaNama}_{$tpsNama}"] = $tps;
                }
            }
        }

        // 4. Ambil Master RekapPartai untuk Partai Ini berdasarkan nomor urut pemilu (default: 11)
        $nomorUrut = (int) ($party['historical_numbers'][2024] ?? 11);
        $partaiDpr = RekapPartai::where('jenis', 'dpr_ri')->where('nomor_urut', $nomorUrut)->first();
        $partaiProv = RekapPartai::where('jenis', 'dprd_prov')->where('nomor_urut', $nomorUrut)->first();
        $partaiKabDapil1 = RekapPartai::where('jenis', 'dprd_kab')->where('dapil_id', $dapil1->id)->where('nomor_urut', $nomorUrut)->first();
        $partaiKabDapil2 = RekapPartai::where('jenis', 'dprd_kab')->where('dapil_id', $dapil2->id)->where('nomor_urut', $nomorUrut)->first();

        // 5. Buat Caleg Partai Ini (DPR RI, DPRD Prov, DPRD Kab)
        $calegsDpr = [
            ['nomor_urut' => 1, 'nama_caleg' => 'Caleg DPR RI 1'],
            ['nomor_urut' => 2, 'nama_caleg' => 'Caleg DPR RI 2'],
            ['nomor_urut' => 3, 'nama_caleg' => 'Caleg DPR RI 3'],
        ];
        foreach ($calegsDpr as $c) {
            if ($partaiDpr) {
                RekapCaleg::firstOrCreate(
                    ['partai_id' => $partaiDpr->id, 'nomor_urut' => $c['nomor_urut']],
                    ['nama_caleg' => $c['nama_caleg']]
                );
            }
        }

        $calegsProv = [
            ['nomor_urut' => 1, 'nama_caleg' => 'Caleg DPRD Prov 1'],
            ['nomor_urut' => 2, 'nama_caleg' => 'Caleg DPRD Prov 2'],
            ['nomor_urut' => 3, 'nama_caleg' => 'Caleg DPRD Prov 3'],
        ];
        foreach ($calegsProv as $c) {
            if ($partaiProv) {
                RekapCaleg::firstOrCreate(
                    ['partai_id' => $partaiProv->id, 'nomor_urut' => $c['nomor_urut']],
                    ['nama_caleg' => $c['nama_caleg']]
                );
            }
        }

        $calegsKabDapil1 = [
            ['nomor_urut' => 1, 'nama_caleg' => 'Caleg DPRD Kab Dapil A 1'],
            ['nomor_urut' => 2, 'nama_caleg' => 'Caleg DPRD Kab Dapil A 2'],
            ['nomor_urut' => 3, 'nama_caleg' => 'Caleg DPRD Kab Dapil A 3'],
        ];
        foreach ($calegsKabDapil1 as $c) {
            if ($partaiKabDapil1) {
                RekapCaleg::firstOrCreate(
                    ['partai_id' => $partaiKabDapil1->id, 'nomor_urut' => $c['nomor_urut']],
                    ['nama_caleg' => $c['nama_caleg']]
                );
            }
        }

        $calegsKabDapil2 = [
            ['nomor_urut' => 1, 'nama_caleg' => 'Caleg DPRD Kab Dapil B 1'],
            ['nomor_urut' => 2, 'nama_caleg' => 'Caleg DPRD Kab Dapil B 2'],
            ['nomor_urut' => 3, 'nama_caleg' => 'Caleg DPRD Kab Dapil B 3'],
        ];
        foreach ($calegsKabDapil2 as $c) {
            if ($partaiKabDapil2) {
                RekapCaleg::firstOrCreate(
                    ['partai_id' => $partaiKabDapil2->id, 'nomor_urut' => $c['nomor_urut']],
                    ['nama_caleg' => $c['nama_caleg']]
                );
            }
        }

        // 6. Buat User Demo
        $kecBwi = Kecamatan::where('nama', 'Banyuwangi')->first();
        $desaLateng = Desa::where('nama', 'Lateng')->first();

        $usersDemo = [
            [
                'name' => 'Koordinator Kecamatan Banyuwangi',
                'username' => 'korcam_banyuwangi',
                'password' => Hash::make('password'),
                'role' => 'korcam',
                'phone' => '081234500001',
                'kecamatan_id' => $kecBwi?->id,
            ],
            [
                'name' => 'Koordinator Desa Lateng',
                'username' => 'kordes_lateng',
                'password' => Hash::make('password'),
                'role' => 'kordes',
                'phone' => '081234500002',
                'desa_id' => $desaLateng?->id,
            ],
            [
                'name' => 'Saksi TPS 01 Lateng',
                'username' => 'saksi_lateng_tps01',
                'password' => Hash::make('password'),
                'role' => 'saksi_tps',
                'phone' => '081234500003',
                'tps_id' => isset($createdTps['Banyuwangi_Lateng_TPS 01']) ? $createdTps['Banyuwangi_Lateng_TPS 01']->id : null,
            ],
            [
                'name' => 'Saksi TPS 02 Lateng',
                'username' => 'saksi_lateng_tps02',
                'password' => Hash::make('password'),
                'role' => 'saksi_tps',
                'phone' => '081234500004',
                'tps_id' => isset($createdTps['Banyuwangi_Lateng_TPS 02']) ? $createdTps['Banyuwangi_Lateng_TPS 02']->id : null,
            ],
            [
                'name' => 'Saksi TPS 01 Badean',
                'username' => 'saksi_badean_tps01',
                'password' => Hash::make('password'),
                'role' => 'saksi_tps',
                'phone' => '081234500005',
                'tps_id' => isset($createdTps['Blimbingsari_Badean_TPS 01']) ? $createdTps['Blimbingsari_Badean_TPS 01']->id : null,
            ],
        ];

        foreach ($usersDemo as $u) {
            if ($u['role'] === 'saksi_tps' && empty($u['tps_id'])) continue;
            User::updateOrCreate(['username' => $u['username']], $u);
        }

        // 7. Input Data Rekapitulasi Suara Tiruan
        $saksi1 = User::where('username', 'saksi_lateng_tps01')->first();
        $saksi2 = User::where('username', 'saksi_lateng_tps02')->first();
        $saksi3 = User::where('username', 'saksi_badean_tps01')->first();

        // --- TPS 01 Lateng (DPR RI & DPRD Kab - Final) ---
        if ($saksi1 && isset($createdTps['Banyuwangi_Lateng_TPS 01'])) {
            $tps = $createdTps['Banyuwangi_Lateng_TPS 01'];

            // DPR RI
            if ($partaiDpr) {
                $rekapDpr = RekapHeader::create([
                    'tps_id' => $tps->id,
                    'jenis' => 'dpr_ri',
                    'status' => 'final',
                    'diinput_oleh' => $saksi1->id,
                    'difinalisasi_at' => $now,
                ]);
                RekapPartaiSuara::create(['rekap_id' => $rekapDpr->id, 'partai_id' => $partaiDpr->id, 'suara' => 45]);
                $calegs = RekapCaleg::where('partai_id', $partaiDpr->id)->get();
                foreach ($calegs as $index => $c) {
                    RekapCalegSuara::create(['rekap_id' => $rekapDpr->id, 'caleg_id' => $c->id, 'suara' => [15, 8, 12][$index] ?? 5]);
                }
            }

            // DPRD Kabupaten (Dapil A)
            if ($partaiKabDapil1) {
                $rekapKab = RekapHeader::create([
                    'tps_id' => $tps->id,
                    'jenis' => 'dprd_kab',
                    'status' => 'final',
                    'diinput_oleh' => $saksi1->id,
                    'difinalisasi_at' => $now,
                ]);
                RekapPartaiSuara::create(['rekap_id' => $rekapKab->id, 'partai_id' => $partaiKabDapil1->id, 'suara' => 38]);
                $calegs = RekapCaleg::where('partai_id', $partaiKabDapil1->id)->get();
                foreach ($calegs as $index => $c) {
                    RekapCalegSuara::create(['rekap_id' => $rekapKab->id, 'caleg_id' => $c->id, 'suara' => [10, 18, 6][$index] ?? 5]);
                }
            }
        }

        // --- TPS 02 Lateng (DPR RI - Draft) ---
        if ($saksi2 && isset($createdTps['Banyuwangi_Lateng_TPS 02'])) {
            $tps = $createdTps['Banyuwangi_Lateng_TPS 02'];

            if ($partaiDpr) {
                $rekapDpr = RekapHeader::create([
                    'tps_id' => $tps->id,
                    'jenis' => 'dpr_ri',
                    'status' => 'draft',
                    'diinput_oleh' => $saksi2->id,
                ]);
                RekapPartaiSuara::create(['rekap_id' => $rekapDpr->id, 'partai_id' => $partaiDpr->id, 'suara' => 20]);
                $calegs = RekapCaleg::where('partai_id', $partaiDpr->id)->get();
                foreach ($calegs as $index => $c) {
                    RekapCalegSuara::create(['rekap_id' => $rekapDpr->id, 'caleg_id' => $c->id, 'suara' => [5, 2, 7][$index] ?? 2]);
                }
            }
        }

        // --- TPS 01 Badean (DPRD Kabupaten - Perlu Dicek) ---
        if ($saksi3 && isset($createdTps['Blimbingsari_Badean_TPS 01'])) {
            $tps = $createdTps['Blimbingsari_Badean_TPS 01'];

            // Blimbingsari masuk Dapil B
            if ($partaiKabDapil2) {
                $rekapKab = RekapHeader::create([
                    'tps_id' => $tps->id,
                    'jenis' => 'dprd_kab',
                    'status' => 'perlu_dicek',
                    'catatan_internal' => 'Jumlah suara caleg melampaui suara sah partai. Harap cek foto C1.',
                    'diinput_oleh' => $saksi3->id,
                ]);
                RekapPartaiSuara::create(['rekap_id' => $rekapKab->id, 'partai_id' => $partaiKabDapil2->id, 'suara' => 12]);
                $calegs = RekapCaleg::where('partai_id', $partaiKabDapil2->id)->get();
                foreach ($calegs as $index => $c) {
                    RekapCalegSuara::create(['rekap_id' => $rekapKab->id, 'caleg_id' => $c->id, 'suara' => [25, 4, 2][$index] ?? 1]);
                }
            }
        }
    }
}
