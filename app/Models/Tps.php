<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tps extends Model
{
    protected $fillable = ['nama', 'desa_id'];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function rekapHeaders(): HasMany
    {
        return $this->hasMany(RekapHeader::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
