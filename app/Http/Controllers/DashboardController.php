<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelatihan;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTrainer()) {
            return $this->trainerDashboard($user);
        } else {
            return $this->karyawanDashboard($user);
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_karyawan'  => User::where('role', 'karyawan')->count(),
            'total_trainer'   => User::where('role', 'trainer')->count(),
            'total_pelatihan' => Pelatihan::count(),
            'total_kategori'  => KategoriPelatihan::count(),
            'pending_daftar'  => Pendaftaran::where('status', 'pending')->count(),
            'pelatihan_aktif' => Pelatihan::where('status', 'ongoing')->count(),
        ];

        $pelatihan_terbaru = Pelatihan::with('kategori', 'trainers')
            ->latest()->take(5)->get();

        $pendaftaran_pending = Pendaftaran::with('user', 'pelatihan')
            ->where('status', 'pending')
            ->latest()->take(10)->get();

        return view('dashboard.admin', compact('stats', 'pelatihan_terbaru', 'pendaftaran_pending'));
    }

    private function trainerDashboard(User $user)
    {
        $pelatihan_saya = $user->pelatihanDiajar()
            ->withCount('peserta')
            ->orderBy('pelatihan.tanggal_mulai', 'desc')
            ->get();

        $stats = [
            'total_pelatihan' => $pelatihan_saya->count(),
            'pelatihan_aktif' => $pelatihan_saya->filter(fn($p) => $p->status === 'ongoing')->count(),
            'total_peserta'   => $pelatihan_saya->sum('peserta_count'),
        ];

        return view('dashboard.trainer', compact('stats', 'pelatihan_saya'));
    }

    private function karyawanDashboard(User $user)
    {
        $pelatihan_diikuti = $user->pelatihanDiikuti()
            ->withPivot('status')
            ->orderBy('pendaftaran.tanggal_daftar', 'desc')
            ->get();

        $sertifikat = $user->sertifikat()->with('pelatihan')->latest()->get();

        $stats = [
            'total_pelatihan'   => $pelatihan_diikuti->count(),
            'pelatihan_selesai' => $pelatihan_diikuti->filter(fn($p) => $p->pivot->status === 'selesai')->count(),
            'total_sertifikat'  => $sertifikat->count(),
        ];

        $pelatihan_tersedia = Pelatihan::where('status', 'published')
            ->whereDoesntHave('peserta', fn($q) => $q->where('user_id', $user->id))
            ->withCount('peserta')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.karyawan', compact('stats', 'pelatihan_diikuti', 'sertifikat', 'pelatihan_tersedia'));
    }
}
