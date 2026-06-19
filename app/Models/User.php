<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
        'kecamatan_id',
        'desa_id',
        'tps_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function roleColor(): string
    {
        return match ($this->role) {
            'admin_partai' => config('party.colors.primary', '#1D4ED8'),
            'korcam' => config('party.colors.korcam', '#F59E0B'),
            'kordes' => config('party.colors.kordes', '#14B8A6'),
            'saksi_tps' => config('party.colors.saksi_tps', '#38BDF8'),
            default => '#666666',
        };
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function tps(): BelongsTo
    {
        return $this->belongsTo(Tps::class);
    }
}
