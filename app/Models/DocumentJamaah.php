<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentJamaah extends Model
{
    use HasFactory;

    protected $table = 'document_jamaahs';

    protected $fillable = [
        'jamaah_id',
        'jenis',
        'file_path',
        'status',
        'alasan_penolakan',
        'verify_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'verify_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }
}
