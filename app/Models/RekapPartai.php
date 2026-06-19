<?php

namespace App\Models;

use App\Support\PartyConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekapPartai extends Model
{
    public const JENIS = ['dpr_ri', 'dprd_prov', 'dprd_kab'];

    protected $fillable = ['jenis', 'nomor_urut', 'nama_partai', 'dapil_id'];

    public function scopeConfiguredParty(Builder $query): Builder
    {
        return PartyConfig::applyPartyQuery($query);
    }

    public function isConfiguredParty(): bool
    {
        return PartyConfig::matchesParty($this->nomor_urut, $this->nama_partai);
    }

    public function calegs(): HasMany
    {
        return $this->hasMany(RekapCaleg::class, 'partai_id')->orderBy('nomor_urut');
    }

    public function suaras(): HasMany
    {
        return $this->hasMany(RekapPartaiSuara::class, 'partai_id');
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }
}
