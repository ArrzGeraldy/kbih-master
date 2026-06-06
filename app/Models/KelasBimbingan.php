<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasBimbingan extends Model
{
    use HasFactory;

    protected $table = 'kelas_bimbingans';

    protected $fillable = [
        'paket_id',
        'nama_kelas',
        'status',
        'mulai_periode',
        'selesai_periode',
        'nama_pembimbing',
    ];

    protected function casts(): array
    {
        return [
            'mulai_periode' => 'date',
            'selesai_periode' => 'date',
        ];
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function sesiBimbingans(): HasMany
    {
        return $this->hasMany(SesiBimbingan::class, 'kelas_bimbingan_id');
    }

    public function orderBimbinganDetails(): HasMany
    {
        return $this->hasMany(OrderBimbinganDetail::class, 'kelas_bimbingan_id');
    }

    public function orderUmrohDetails(): HasMany
    {
        return $this->hasMany(OrderUmrohDetail::class, 'kelas_bimbingan_id');
    }
}
