<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBimbinganDetail extends Model
{
    use HasFactory;

    protected $table = 'order_bimbingan_details';

    protected $primaryKey = 'order_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'nomor_porsi',
        'kelas_bimbingan_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function kelasBimbingan(): BelongsTo
    {
        return $this->belongsTo(KelasBimbingan::class, 'kelas_bimbingan_id');
    }
}
