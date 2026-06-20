@extends('layouts.app')
@section('title', 'Kategori Pelatihan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kategori Pelatihan</h5>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nama</th>
                    <th>Deskripsi</th>
                    <th>Jml Pelatihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kat)
                <tr>
                    <td class="ps-3 fw-medium">{{ $kat->nama }}</td>
                    <td class="small text-muted">{{ Str::limit($kat->deskripsi, 60) ?? '-' }}</td>
                    <td>{{ $kat->pelatihan_count }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('kategori.edit', $kat) }}" class="btn btn-sm btn-outline-primary py-0">Edit</a>
                            <form method="POST" action="{{ route('kategori.destroy', $kat) }}"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $kategoris->links() }}</div>
@endsection
