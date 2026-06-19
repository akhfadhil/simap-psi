<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dapil extends Model
{
    protected $fillable = ['nama'];

    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }

    public function partais(): HasMany
    {
        return $this->hasMany(RekapPartai::class);
    }
}
