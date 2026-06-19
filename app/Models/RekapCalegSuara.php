<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapCalegSuara extends Model
{
    protected $fillable = ['rekap_id', 'caleg_id', 'suara'];

    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapHeader::class, 'rekap_id');
    }

    public function caleg(): BelongsTo
    {
        return $this->belongsTo(RekapCaleg::class, 'caleg_id');
    }
}
