<?php

namespace App\Http\Controllers;

use App\Models\MateriPelatihan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index(Pelatihan $pelatihan)
    {
        $pelatihan->load('materi');
        return view('materi.index', compact('pelatihan'));
    }

    public function create(Pelatihan $pelatihan)
    {
        return view('materi.create', compact('pelatihan'));
    }

    public function store(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xlsx,zip', 'max:20480'],
            'link_video'   => ['nullable', 'url'],
            'urutan'       => ['required', 'integer', 'min:0'],
            'durasi_menit' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')
                ->store("materi/{$pelatihan->id}", 'public');
        }

        $validated['pelatihan_id'] = $pelatihan->id;
        MateriPelatihan::create($validated);

        return redirect()->route('pelatihan.materi.index', $pelatihan)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(Pelatihan $pelatihan, MateriPelatihan $materi)
    {
        $user = Auth::user();

        // Update progress otomatis saat materi dibuka (many-to-many pivot)
        if ($user->isKaryawan()) {
            $existing = $user->materiProgress()
                ->wherePivot('materi_id', $materi->id)
                ->first();

            if (!$existing) {
                $user->materiProgress()->attach($materi->id, [
                    'selesai'    => false,
                    'persentase' => 0,
                ]);
            }
        }

        $progress = $user->materiProgress()
            ->wherePivot('materi_id', $materi->id)
            ->first()?->pivot;

        return view('materi.show', compact('pelatihan', 'materi', 'progress'));
    }

    public function edit(Pelatihan $pelatihan, MateriPelatihan $materi)
    {
        return view('materi.edit', compact('pelatihan', 'materi'));
    }

    public function update(Request $request, Pelatihan $pelatihan, MateriPelatihan $materi)
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xlsx,zip', 'max:20480'],
            'link_video'   => ['nullable', 'url'],
            'urutan'       => ['required', 'integer', 'min:0'],
            'durasi_menit' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('file')) {
            if ($materi->file) Storage::disk('public')->delete($materi->file);
            $validated['file'] = $request->file('file')
                ->store("materi/{$pelatihan->id}", 'public');
        }

        $materi->update($validated);

        return redirect()->route('pelatihan.materi.index', $pelatihan)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Pelatihan $pelatihan, MateriPelatihan $materi)
    {
        if ($materi->file) Storage::disk('public')->delete($materi->file);
        $materi->delete();

        return redirect()->route('pelatihan.materi.index', $pelatihan)
            ->with('success', 'Materi berhasil dihapus.');
    }

    // Tandai materi selesai (many-to-many pivot update)
    public function selesaikan(Pelatihan $pelatihan, MateriPelatihan $materi)
    {
        $user = Auth::user();

        $user->materiProgress()->syncWithoutDetaching([
            $materi->id => [
                'selesai'         => true,
                'persentase'      => 100,
                'tanggal_selesai' => now(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Materi ditandai selesai.']);
    }
}
