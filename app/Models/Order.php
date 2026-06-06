<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'jamaah_id',
        'paket_id',
        'status',
        'harga_snapshot',
        'total_tagihan',
        'durasi_cicilan',
        'total_dibayar',
    ];

    protected function casts(): array
    {
        return [
            'harga_snapshot' => 'integer',
            'total_tagihan' => 'integer',
            'durasi_cicilan' => 'integer',
            'total_dibayar' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function orderBimbinganDetail(): HasOne
    {
        return $this->hasOne(OrderBimbinganDetail::class, 'order_id');
    }

    public function orderUmrohDetail(): HasOne
    {
        return $this->hasOne(OrderUmrohDetail::class, 'order_id');
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'order_id');
    }
}
