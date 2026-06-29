<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'nip', 'phone', 'department',
        'position', 'role', 'photo', 'password', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // MANY-TO-MANY: Karyawan ↔ Pelatihan (sebagai peserta)
    // Seorang karyawan bisa ikut banyak pelatihan
    // Satu pelatihan diikuti banyak karyawan
    // Pivot table: pendaftaran
    // -------------------------------------------------------
    public function pelatihanDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'pendaftaran', 'user_id', 'pelatihan_id')
                    ->withPivot(['status', 'catatan', 'tanggal_daftar', 'tanggal_diproses'])
                    ->withTimestamps();
    }

    // -------------------------------------------------------
    // MANY-TO-MANY: Trainer ↔ Pelatihan (sebagai pengajar)
    // Seorang trainer bisa mengajar banyak pelatihan
    // Satu pelatihan bisa punya banyak trainer
    // Pivot table: pelatihan_trainer
    // -------------------------------------------------------
    public function pelatihanDiajar(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'pelatihan_trainer', 'trainer_id', 'pelatihan_id')
                    ->withPivot(['peran'])
                    ->withTimestamps();
    }

    // -------------------------------------------------------
    // MANY-TO-MANY: Peserta ↔ MateriPelatihan (progress belajar)
    // Seorang peserta punya progress di banyak materi
    // Satu materi bisa dicapai banyak peserta
    // Pivot table: materi_progress
    // -------------------------------------------------------
    public function materiProgress(): BelongsToMany
    {
        return $this->belongsToMany(MateriPelatihan::class, 'materi_progress', 'user_id', 'materi_id')
                    ->withPivot(['selesai', 'persentase', 'tanggal_selesai'])
                    ->withTimestamps();
    }

    // -------------------------------------------------------
    // ONE-TO-MANY
    // -------------------------------------------------------
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
    // ONE-TO-ONE: Trainer ↔ TrainerProfile
    // Satu user (trainer) hanya punya satu profil tambahan
    // Satu profil hanya milik satu user (FK user_id unique)
    // -------------------------------------------------------
    public function trainerProfile(): HasOne
    {
        return $this->hasOne(TrainerProfile::class);
    }

    // -------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }
}