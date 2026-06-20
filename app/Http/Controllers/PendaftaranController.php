<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    // Daftar pendaftaran — admin: semua, trainer: hanya pelatihan yang dia ajar
    public function index(Request $request)
    {
        $user  = Auth::user();

        $pendaftaran = Pendaftaran::with('user', 'pelatihan')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->pelatihan_id, fn($q) => $q->where('pelatihan_id', $request->pelatihan_id))
            // Trainer hanya lihat pendaftaran di pelatihan yang dia ajar
            ->when($user->isTrainer(), fn($q) =>
                $q->whereHas('pelatihan.trainers', fn($t) => $t->where('users.id', $user->id))
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pendaftaran.index', compact('pendaftaran'));
    }

    // Karyawan daftar ke pelatihan (many-to-many attach)
    public function daftar(Request $request, Pelatihan $pelatihan)
    {
        $user = Auth::user();

        // Cek sudah terdaftar
        $sudahDaftar = $pelatihan->peserta()
            ->wherePivot('user_id', $user->id)
            ->exists();

        if ($sudahDaftar) {
            return back()->with('error', 'Anda sudah terdaftar di pelatihan ini.');
        }

        // Cek kuota
        if ($pelatihan->isKuotaPenuh()) {
            return back()->with('error', 'Kuota pelatihan sudah penuh.');
        }

        // Attach via many-to-many
        $pelatihan->peserta()->attach($user->id, [
            'status'        => 'pending',
            'tanggal_daftar'=> now(),
        ]);

        return back()->with('success', 'Pendaftaran berhasil dikirim, menunggu persetujuan.');
    }

    // Batalkan pendaftaran (karyawan)
    public function batalkan(Pelatihan $pelatihan)
    {
        $user = Auth::user();

        $pendaftaran = Pendaftaran::where('pelatihan_id', $pelatihan->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Detach via many-to-many
        $pelatihan->peserta()->detach($user->id);

        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // Setujui / tolak pendaftaran (admin/trainer)
    public function proses(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status'  => ['required', 'in:disetujui,ditolak'],
            'catatan' => ['nullable', 'string'],
        ]);

        // Trainer hanya boleh proses pendaftaran di pelatihan yang dia ajar
        $user = Auth::user();
        if ($user->isTrainer()) {
            $isTrainernya = $pendaftaran->pelatihan->trainers()
                ->where('users.id', $user->id)->exists();
            if (!$isTrainernya) {
                abort(403, 'Anda tidak berhak memproses pendaftaran ini.');
            }
        }

        // Update pivot (many-to-many)
        $pendaftaran->pelatihan->peserta()->updateExistingPivot($pendaftaran->user_id, [
            'status'           => $request->status,
            'catatan'          => $request->catatan,
            'tanggal_diproses' => now(),
        ]);

        if ($request->status === 'disetujui') {
            Penilaian::firstOrCreate([
                'pelatihan_id' => $pendaftaran->pelatihan_id,
                'user_id'      => $pendaftaran->user_id,
            ], ['status_kelulusan' => 'belum_dinilai']);
        }

        $label = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pendaftaran berhasil {$label}.");
    }

    // Bulk proses (admin & trainer — trainer hanya pelatihan miliknya)
    public function bulkProses(Request $request)
    {
        $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['exists:pendaftaran,id'],
            'status' => ['required', 'in:disetujui,ditolak'],
        ]);

        $user         = Auth::user();
        $pendaftarans = Pendaftaran::whereIn('id', $request->ids)->get();

        foreach ($pendaftarans as $p) {
            // Trainer hanya boleh proses miliknya
            if ($user->isTrainer()) {
                $isTrainernya = $p->pelatihan->trainers()
                    ->where('users.id', $user->id)->exists();
                if (!$isTrainernya) continue;
            }

            $p->pelatihan->peserta()->updateExistingPivot($p->user_id, [
                'status'           => $request->status,
                'tanggal_diproses' => now(),
            ]);

            if ($request->status === 'disetujui') {
                Penilaian::firstOrCreate([
                    'pelatihan_id' => $p->pelatihan_id,
                    'user_id'      => $p->user_id,
                ], ['status_kelulusan' => 'belum_dinilai']);
            }
        }

        return back()->with('success', count($request->ids) . ' pendaftaran berhasil diproses.');
    }
}
