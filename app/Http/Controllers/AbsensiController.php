<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Pelatihan $pelatihan, Request $request)
    {
        $tanggal = $request->tanggal ?? today()->toDateString();

        // Ambil peserta yang disetujui (many-to-many)
        $peserta = $pelatihan->pesertaDisetujui()->orderBy('name')->get();

        // Absensi hari ini
        $absensi = Absensi::where('pelatihan_id', $pelatihan->id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('user_id');

        return view('absensi.index', compact('pelatihan', 'peserta', 'absensi', 'tanggal'));
    }

    public function store(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'tanggal'         => ['required', 'date'],
            'absensi'         => ['required', 'array'],
            'absensi.*.user_id' => ['required', 'exists:users,id'],
            'absensi.*.status'  => ['required', 'in:hadir,izin,sakit,alpha'],
            'absensi.*.jam_masuk'  => ['nullable', 'date_format:H:i'],
            'absensi.*.jam_keluar' => ['nullable', 'date_format:H:i'],
        ]);

        foreach ($request->absensi as $data) {
            Absensi::updateOrCreate(
                [
                    'pelatihan_id' => $pelatihan->id,
                    'user_id'      => $data['user_id'],
                    'tanggal'      => $request->tanggal,
                ],
                [
                    'status'     => $data['status'],
                    'jam_masuk'  => $data['jam_masuk'] ?? null,
                    'jam_keluar' => $data['jam_keluar'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function rekap(Pelatihan $pelatihan)
    {
        // Peserta + semua absensinya
        $peserta = $pelatihan->pesertaDisetujui()
            ->with(['absensi' => fn($q) => $q->where('pelatihan_id', $pelatihan->id)])
            ->get()
            ->map(function ($user) use ($pelatihan) {
                $absensiUser = $user->absensi->where('pelatihan_id', $pelatihan->id);
                return [
                    'user'       => $user,
                    'hadir'      => $absensiUser->where('status', 'hadir')->count(),
                    'izin'       => $absensiUser->where('status', 'izin')->count(),
                    'sakit'      => $absensiUser->where('status', 'sakit')->count(),
                    'alpha'      => $absensiUser->where('status', 'alpha')->count(),
                    'total'      => $absensiUser->count(),
                    'persentase' => $absensiUser->count()
                        ? round($absensiUser->where('status', 'hadir')->count() / $absensiUser->count() * 100, 1)
                        : 0,
                ];
            });

        return view('absensi.rekap', compact('pelatihan', 'peserta'));
    }
}
