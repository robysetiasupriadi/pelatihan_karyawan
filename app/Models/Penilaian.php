<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'pelatihan_id', 'user_id',
        'nilai_pre_test', 'nilai_post_test',
        'nilai_tugas', 'nilai_kehadiran', 'nilai_akhir',
        'grade', 'status_kelulusan', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_pre_test'   => 'decimal:2',
            'nilai_post_test'  => 'decimal:2',
            'nilai_tugas'      => 'decimal:2',
            'nilai_kehadiran'  => 'decimal:2',
            'nilai_akhir'      => 'decimal:2',
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

    /**
     * Hitung nilai akhir dan grade otomatis.
     * Bobot: pre_test 10%, post_test 40%, tugas 30%, kehadiran 20%
     */
    public function hitungNilaiAkhir(): void
    {
        $nilai = (($this->nilai_pre_test ?? 0) * 0.10)
               + (($this->nilai_post_test ?? 0) * 0.40)
               + (($this->nilai_tugas ?? 0) * 0.30)
               + (($this->nilai_kehadiran ?? 0) * 0.20);

        $this->nilai_akhir = round($nilai, 2);

        $this->grade = match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 70 => 'B',
            $nilai >= 55 => 'C',
            $nilai >= 40 => 'D',
            default      => 'E',
        };

        $this->status_kelulusan = $nilai >= 55 ? 'lulus' : 'tidak_lulus';
    }
}
