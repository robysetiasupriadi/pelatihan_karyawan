<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelatihan;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $pelatihan = Pelatihan::with('kategori', 'trainers')
            ->withCount('peserta')
            ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('kode', 'like', "%{$request->search}%"))
            ->when($request->kategori_id, fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->metode, fn($q) => $q->where('metode', $request->metode))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $kategoris = KategoriPelatihan::orderBy('nama')->get();

        return view('pelatihan.index', compact('pelatihan', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriPelatihan::orderBy('nama')->get();
        $trainers  = User::where('role', 'trainer')->where('is_active', true)->orderBy('name')->get();
        return view('pelatihan.create', compact('kategoris', 'trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'kategori_id'     => ['required', 'exists:kategori_pelatihan,id'],
            'trainer_ids'     => ['required', 'array', 'min:1'],
            'trainer_ids.*'   => ['exists:users,id'],
            'trainer_perans'  => ['nullable', 'array'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai'       => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_selesai'     => ['nullable', 'date_format:H:i,H:i:s'],
            'lokasi'          => ['nullable', 'string', 'max:255'],
            'kuota'           => ['required', 'integer', 'min:1'],
            'metode'          => ['required', 'in:online,offline,hybrid'],
            'link_meeting'    => ['nullable', 'url'],
            'status'          => ['required', 'in:draft,published,ongoing,selesai,dibatalkan'],
            'biaya'           => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['kode'] = $this->generateKode();
        $validated['biaya'] = $validated['biaya'] ?? 0;

        $pelatihan = Pelatihan::create($validated);

        // Sync trainers (many-to-many)
        $trainerSync = [];
        foreach ($request->trainer_ids as $i => $trainerId) {
            $peran = $request->trainer_perans[$i] ?? 'trainer';
            $trainerSync[$trainerId] = ['peran' => $peran];
        }
        $pelatihan->trainers()->sync($trainerSync);

        return redirect()->route('pelatihan.show', $pelatihan)
            ->with('success', 'Pelatihan berhasil dibuat.');
    }

    public function show(Pelatihan $pelatihan)
    {
        $pelatihan->load([
            'kategori',
            'trainers',
            'materi',
            'peserta' => fn($q) => $q->withPivot('status'),
            'pendaftaran.user',
        ]);
        $pelatihan->loadCount('peserta');

        return view('pelatihan.show', compact('pelatihan'));
    }

    public function edit(Pelatihan $pelatihan)
    {
        $kategoris = KategoriPelatihan::orderBy('nama')->get();
        $trainers  = User::where('role', 'trainer')->where('is_active', true)->orderBy('name')->get();
        $pelatihan->load('trainers');
        return view('pelatihan.edit', compact('pelatihan', 'kategoris', 'trainers'));
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'kategori_id'     => ['required', 'exists:kategori_pelatihan,id'],
            'trainer_ids'     => ['required', 'array', 'min:1'],
            'trainer_ids.*'   => ['exists:users,id'],
            'trainer_perans'  => ['nullable', 'array'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai'       => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_selesai'     => ['nullable', 'date_format:H:i,H:i:s'],
            'lokasi'          => ['nullable', 'string', 'max:255'],
            'kuota'           => ['required', 'integer', 'min:1'],
            'metode'          => ['required', 'in:online,offline,hybrid'],
            'link_meeting'    => ['nullable', 'url'],
            'status'          => ['required', 'in:draft,published,ongoing,selesai,dibatalkan'],
            'biaya'           => ['nullable', 'numeric', 'min:0'],
        ]);

        $pelatihan->update($validated);

        // Sync trainers (many-to-many)
        $trainerSync = [];
        foreach ($request->trainer_ids as $i => $trainerId) {
            $peran = $request->trainer_perans[$i] ?? 'trainer';
            $trainerSync[$trainerId] = ['peran' => $peran];
        }
        $pelatihan->trainers()->sync($trainerSync);

        return redirect()->route('pelatihan.show', $pelatihan)
            ->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'status' => ['required', 'in:draft,published,ongoing,selesai,dibatalkan'],
        ]);

        $pelatihan->update(['status' => $request->status]);

        return back()->with('success', 'Status berhasil diubah.');
    }

    public function destroy(Pelatihan $pelatihan)
    {
        $pelatihan->delete();
        return redirect()->route('pelatihan.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $prefix = 'PLT-' . date('Y') . '-';
        $last   = Pelatihan::where('kode', 'like', $prefix . '%')->count() + 1;
        return $prefix . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}