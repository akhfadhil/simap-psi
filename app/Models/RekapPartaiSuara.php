<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapPartaiSuara extends Model
{
    protected $fillable = ['rekap_id', 'partai_id', 'suara'];

    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapHeader::class, 'rekap_id');
    }

    public function partai(): BelongsTo
    {
        return $this->belongsTo(RekapPartai::class, 'partai_id');
    }
}
