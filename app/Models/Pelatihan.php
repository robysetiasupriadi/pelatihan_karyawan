<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    protected $table = 'pelatihan';

    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'kategori_id',
        'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai',
        'lokasi', 'kuota', 'metode', 'link_meeting',
        'status', 'cover', 'biaya',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'biaya'           => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // MANY-TO-MANY: Pelatihan ↔ Trainer (User)
    // Satu pelatihan bisa punya banyak trainer
    // Seorang trainer bisa mengajar banyak pelatihan
    // Pivot table: pelatihan_trainer
    // -------------------------------------------------------
    public function trainers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pelatihan_trainer', 'pelatihan_id', 'trainer_id')
                    ->withPivot(['peran'])
                    ->withTimestamps();
    }

    // -------------------------------------------------------
    // MANY-TO-MANY: Pelatihan ↔ Peserta (User)
    // Satu pelatihan diikuti banyak karyawan
    // Seorang karyawan bisa ikut banyak pelatihan
    // Pivot table: pendaftaran
    // -------------------------------------------------------
    public function peserta(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pendaftaran', 'pelatihan_id', 'user_id')
                    ->withPivot(['status', 'catatan', 'tanggal_daftar', 'tanggal_diproses'])
                    ->withTimestamps();
    }

    // Hanya peserta yang sudah disetujui
    public function pesertaDisetujui(): BelongsToMany
    {
        return $this->peserta()->wherePivot('status', 'disetujui');
    }

    // -------------------------------------------------------
    // ONE-TO-MANY
    // -------------------------------------------------------
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPelatihan::class, 'kategori_id');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(MateriPelatihan::class)->orderBy('urutan');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    // -------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------
    public function getSisaKuotaAttribute(): int
    {
        $terisi = $this->pendaftaran()->where('status', 'disetujui')->count();
        return max(0, $this->kuota - $terisi);
    }

    public function isKuotaPenuh(): bool
    {
        $terisi = $this->pendaftaran()->where('status', 'disetujui')->count();
        return $terisi >= $this->kuota;
    }
}
