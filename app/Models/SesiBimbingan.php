<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SesiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'sesi_bimbingans';

    protected $fillable = [
        'kelas_bimbingan_id',
        'judul',
        'mulai_at',
        'selesai_at',
        'lokasi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'mulai_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    public function kelasBimbingan(): BelongsTo
    {
        return $this->belongsTo(KelasBimbingan::class, 'kelas_bimbingan_id');
    }

}
