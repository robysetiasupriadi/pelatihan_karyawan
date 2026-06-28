<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pelatihan;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    // Daftar sertifikat milik karyawan yang login
    public function milik()
    {
        $sertifikat = Auth::user()->sertifikat()
            ->with('pelatihan')
            ->latest()
            ->paginate(12);

        return view('sertifikat.milik', compact('sertifikat'));
    }

    // Daftar sertifikat per pelatihan (admin)
    public function index(Pelatihan $pelatihan)
    {
        $sertifikat = Sertifikat::with('user')
            ->where('pelatihan_id', $pelatihan->id)
            ->latest()
            ->get();

        return view('sertifikat.index', compact('pelatihan', 'sertifikat'));
    }

    // Generate sertifikat untuk semua peserta lulus
    public function generate(Pelatihan $pelatihan)
    {
        $lulus = Penilaian::where('pelatihan_id', $pelatihan->id)
            ->where('status_kelulusan', 'lulus')
            ->get();

        $count = 0;

        foreach ($lulus as $p) {
            // Skip kalau sertifikat untuk peserta ini sudah ada (hindari kerja sia-sia)
            $sudahAda = Sertifikat::where('pelatihan_id', $pelatihan->id)
                ->where('user_id', $p->user_id)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            try {
                DB::transaction(function () use ($pelatihan, $p) {
                    Sertifikat::create([
                        'pelatihan_id'   => $pelatihan->id,
                        'user_id'        => $p->user_id,
                        'nomor'          => $this->generateNomorUnik($pelatihan),
                        'tanggal_terbit' => today(),
                    ]);
                });
                $count++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Race condition / nomor collide di percobaan terakhir — lewati,
                // peserta ini bisa di-generate ulang dengan aman di klik berikutnya.
                continue;
            }
        }

        return back()->with('success', "{$count} sertifikat berhasil digenerate.");
    }

    public function show(Pelatihan $pelatihan, Sertifikat $sertifikat)
    {
    // Pastikan hanya pemilik atau admin yang bisa lihat
    if (Auth::user()->isKaryawan() && $sertifikat->user_id !== Auth::id()) {
        abort(403);
    }

    $sertifikat->load('user', 'pelatihan.trainers');
    return view('sertifikat.show', compact('sertifikat'));
    }

    public function destroy(Pelatihan $pelatihan, Sertifikat $sertifikat)
{
    $sertifikat->delete();

    return redirect()->route('pelatihan.sertifikat.index', $pelatihan)
        ->with('success', 'Sertifikat berhasil dihapus.');
}
    /**
     * Generate nomor sertifikat yang dipastikan unik.
     *
     * Format: SERT/{tahun}/{kode-pelatihan}/{urutan 4 digit}
     * Urutan dihitung dengan row-lock per pelatihan supaya aman dari
     * race condition (dua request generate bersamaan), dan tetap
     * di-cek ulang ke tabel sertifikat sebagai pengaman kedua.
     */
    private function generateNomorUnik(Pelatihan $pelatihan): string
    {
        $tahun = date('Y');

        // Ambil kode pelatihan apa adanya (sudah unik per pelatihan di DB),
        // hanya dibersihkan dari karakter selain huruf/angka/strip.
        $kode = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $pelatihan->kode));

        // Lock baris2 sertifikat pelatihan ini agar urutan tidak dobel
        // ketika ada dua request generate berjalan bersamaan.
        $urut = Sertifikat::where('pelatihan_id', $pelatihan->id)
            ->lockForUpdate()
            ->count() + 1;

        do {
            $nomor = sprintf('SERT/%s/%s/%04d', $tahun, $kode, $urut);
            $exists = Sertifikat::where('nomor', $nomor)->exists();
            $urut++;
        } while ($exists);

        return $nomor;
    }
}