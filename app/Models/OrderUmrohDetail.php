<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderUmrohDetail extends Model
{
     use HasFactory;

    protected $table = 'order_umroh_details';

    protected $primaryKey = 'order_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'tanggal_keberangkatan',
        'kelas_bimbingan_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_keberangkatan' => 'date',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function kelasBimbingan(): BelongsTo
    {
        return $this->belongsTo(KelasBimbingan::class, 'kelas_bimbingan_id');
    }
}
