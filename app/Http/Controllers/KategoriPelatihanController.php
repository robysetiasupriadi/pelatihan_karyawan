<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelatihan;
use Illuminate\Http\Request;

class KategoriPelatihanController extends Controller
{
    public function index()
    {
        $kategoris = KategoriPelatihan::withCount('pelatihan')->orderBy('nama')->paginate(15);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => ['required', 'string', 'max:100', 'unique:kategori_pelatihan'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        KategoriPelatihan::create($request->only('nama', 'deskripsi'));

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriPelatihan $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriPelatihan $kategori)
    {
        $request->validate([
            'nama'      => ['required', 'string', 'max:100', "unique:kategori_pelatihan,nama,{$kategori->id}"],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kategori->update($request->only('nama', 'deskripsi'));

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriPelatihan $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
