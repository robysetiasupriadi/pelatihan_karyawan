<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Pelatihan $pelatihan)
    {
        $penilaian = Penilaian::with('user')
            ->where('pelatihan_id', $pelatihan->id)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        return view('penilaian.index', compact('pelatihan', 'penilaian'));
    }

    public function edit(Pelatihan $pelatihan, Penilaian $penilaian)
    {
        return view('penilaian.edit', compact('pelatihan', 'penilaian'));
    }

    public function update(Request $request, Pelatihan $pelatihan, Penilaian $penilaian)
    {
        $request->validate([
            'nilai_pre_test'  => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_post_test' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_tugas'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_kehadiran' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'catatan'         => ['nullable', 'string'],
        ]);

        $penilaian->fill($request->only(
            'nilai_pre_test', 'nilai_post_test',
            'nilai_tugas', 'nilai_kehadiran', 'catatan'
        ));

        $penilaian->hitungNilaiAkhir();
        $penilaian->save();

        return redirect()->route('pelatihan.penilaian.index', $pelatihan)
            ->with('success', 'Nilai berhasil disimpan.');
    }


    public function bulkUpdate(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'penilaian'                  => ['required', 'array'],
            'penilaian.*.id'             => ['required', 'exists:penilaian,id'],
            'penilaian.*.nilai_pre_test' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'penilaian.*.nilai_post_test'=> ['nullable', 'numeric', 'min:0', 'max:100'],
            'penilaian.*.nilai_tugas'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'penilaian.*.nilai_kehadiran'=> ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($request->penilaian as $data) {
            $p = Penilaian::find($data['id']);
            $p->fill([
                'nilai_pre_test'  => $data['nilai_pre_test'] ?? null,
                'nilai_post_test' => $data['nilai_post_test'] ?? null,
                'nilai_tugas'     => $data['nilai_tugas'] ?? null,
                'nilai_kehadiran' => $data['nilai_kehadiran'] ?? null,
            ]);
            $p->hitungNilaiAkhir();
            $p->save();
        }

        return back()->with('success', 'Semua nilai berhasil disimpan.');
    }
}
