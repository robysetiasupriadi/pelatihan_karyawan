<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';

    protected $fillable = [
        'nomor', 'pelatihan_id', 'user_id',
        'tanggal_terbit', 'tanggal_kadaluarsa', 'file',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terbit'      => 'date',
            'tanggal_kadaluarsa'  => 'date',
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
