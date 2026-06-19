<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekapCaleg extends Model
{
    protected $fillable = ['partai_id', 'nomor_urut', 'nama_caleg'];

    public function partai(): BelongsTo
    {
        return $this->belongsTo(RekapPartai::class, 'partai_id');
    }

    public function suaras(): HasMany
    {
        return $this->hasMany(RekapCalegSuara::class, 'caleg_id');
    }
}
