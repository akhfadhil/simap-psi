<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $fillable = ['nama', 'kecamatan_id'];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function tps(): HasMany
    {
        return $this->hasMany(Tps::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pendukungs(): HasMany
    {
        return $this->hasMany(Pendukung::class);
    }
}
