<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'pakets';

    protected $fillable = [
        'nama_paket',
        'type',
        'fasilitas',
        'description',
        'harga',
        'dp',
        'minimum_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'fasilitas' => 'array',
            'harga' => 'integer',
            'dp' => 'integer',
            'minimum_pembayaran' => 'integer',
        ];
    }

    public function kelasBimbingans(): HasMany
    {
        return $this->hasMany(KelasBimbingan::class, 'paket_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'paket_id');
    }
}
