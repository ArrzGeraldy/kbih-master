<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'order_id',
        'tipe',
        'tanggal_bayar',
        'jumlah',
        'metode',
        'status',
        'bukti_path',
        'gateway',
        'midtrans_order_id',
        'snap_token',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'gateway_payload',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'jumlah' => 'integer',
            'gateway_payload' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
