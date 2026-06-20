<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pelatihan;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            Sertifikat::firstOrCreate(
                ['pelatihan_id' => $pelatihan->id, 'user_id' => $p->user_id],
                [
                    'nomor'          => $this->generateNomor($pelatihan),
                    'tanggal_terbit' => today(),
                ]
            );
            $count++;
        }

        return back()->with('success', "{$count} sertifikat berhasil digenerate.");
    }

    public function show(Sertifikat $sertifikat)
    {
        // Pastikan hanya pemilik atau admin yang bisa lihat
        if (Auth::user()->isKaryawan() && $sertifikat->user_id !== Auth::id()) {
            abort(403);
        }

        $sertifikat->load('user', 'pelatihan.trainers');
        return view('sertifikat.show', compact('sertifikat'));
    }

    public function destroy(Sertifikat $sertifikat)
    {
        // Load relasi sebelum delete — hindari null jika orphan record
        $pelatihan = $sertifikat->pelatihan()->firstOrFail();
        $sertifikat->delete();

        return redirect()->route('pelatihan.sertifikat.index', $pelatihan)
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    private function generateNomor(Pelatihan $pelatihan): string
    {
        $count  = Sertifikat::where('pelatihan_id', $pelatihan->id)->count() + 1;
        $tahun  = date('Y');
        $kode   = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $pelatihan->kode), 0, 8));
        return "SERT/{$tahun}/{$kode}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
