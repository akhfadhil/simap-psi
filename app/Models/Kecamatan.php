<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = ['nama', 'dapil_id'];

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function pendukungs(): HasMany
    {
        return $this->hasMany(Pendukung::class);
    }
}
