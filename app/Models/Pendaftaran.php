<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pivot model untuk relasi many-to-many User ↔ Pelatihan (sebagai peserta).
 * Digunakan ketika perlu query langsung ke tabel pendaftaran.
 */
class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'pelatihan_id', 'user_id', 'status',
        'catatan', 'tanggal_daftar', 'tanggal_diproses',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar'    => 'datetime',
            'tanggal_diproses'  => 'datetime',
        ];
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
