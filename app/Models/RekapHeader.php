<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekapHeader extends Model
{
    public const LEGISLATIVE_TYPES = ['dpr_ri', 'dprd_prov', 'dprd_kab'];

    public const JENIS_LABELS = [
        'dpr_ri' => 'DPR RI',
        'dprd_prov' => 'DPRD Provinsi',
        'dprd_kab' => 'DPRD Kabupaten',
    ];

    protected $fillable = [
        'tps_id',
        'jenis',
        'dpt_lk',
        'dpt_pr',
        'pengguna_dpt_lk',
        'pengguna_dpt_pr',
        'pengguna_dptb_lk',
        'pengguna_dptb_pr',
        'pengguna_dpk_lk',
        'pengguna_dpk_pr',
        'ss_diterima',
        'ss_digunakan',
        'ss_rusak',
        'ss_sisa',
        'disabilitas_lk',
        'disabilitas_pr',
        'suara_tidak_sah',
        'status',
        'catatan_internal',
        'diinput_oleh',
        'difinalisasi_at',
    ];

    protected $casts = [
        'difinalisasi_at' => 'datetime',
    ];

    public function tps(): BelongsTo
    {
        return $this->belongsTo(Tps::class);
    }

    public function inputBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function partaiSuaras(): HasMany
    {
        return $this->hasMany(RekapPartaiSuara::class, 'rekap_id');
    }

    public function calegSuaras(): HasMany
    {
        return $this->hasMany(RekapCalegSuara::class, 'rekap_id');
    }

    public function getSuaraSahAttribute(): int
    {
        return $this->partaiSuaras->sum('suara') + $this->calegSuaras->sum('suara');
    }

    public function getTotalPenggunaLkAttribute(): int
    {
        return $this->pengguna_dpt_lk + $this->pengguna_dptb_lk + $this->pengguna_dpk_lk;
    }

    public function getTotalPenggunaPrAttribute(): int
    {
        return $this->pengguna_dpt_pr + $this->pengguna_dptb_pr + $this->pengguna_dpk_pr;
    }
}
