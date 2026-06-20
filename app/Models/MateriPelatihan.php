<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MateriPelatihan extends Model
{
    protected $table = 'materi_pelatihan';

    protected $fillable = [
        'pelatihan_id', 'judul', 'deskripsi',
        'file', 'link_video', 'urutan', 'durasi_menit',
    ];

    // -------------------------------------------------------
    // MANY-TO-MANY: MateriPelatihan ↔ Peserta (User)
    // Satu materi bisa dicapai/diakses banyak peserta
    // Seorang peserta punya progress di banyak materi
    // Pivot table: materi_progress
    // -------------------------------------------------------
    public function pesertaProgress(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'materi_progress', 'materi_id', 'user_id')
                    ->withPivot(['selesai', 'persentase', 'tanggal_selesai'])
                    ->withTimestamps();
    }

    // Hanya peserta yang sudah menyelesaikan materi ini
    public function pesertaSelesai(): BelongsToMany
    {
        return $this->pesertaProgress()->wherePivot('selesai', true);
    }

    // -------------------------------------------------------
    // ONE-TO-MANY
    // -------------------------------------------------------
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }
}
