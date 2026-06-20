@extends('layouts.app')
@section('title', 'Sertifikat')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.show', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Sertifikat — {{ $pelatihan->nama }}</h5>
    <form method="POST" action="{{ route('pelatihan.sertifikat.generate', $pelatihan) }}" class="ms-auto">
        @csrf
        <button class="btn btn-warning btn-sm">
            <i class="bi bi-award me-1"></i>Generate Sertifikat Lulus
        </button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Peserta</th>
                    <th>No. Sertifikat</th>
                    <th>Tgl Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sertifikat as $s)
                <tr>
                    <td class="ps-3 fw-medium small">{{ $s->user->name }}</td>
                    <td class="small"><code>{{ $s->nomor }}</code></td>
                    <td class="small text-muted">{{ $s->tanggal_terbit->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('sertifikat.show', $s) }}" class="btn btn-sm btn-outline-primary py-0">Lihat</a>
                            <form method="POST" action="{{ route('pelatihan.sertifikat.destroy', [$pelatihan, $s]) }}"
                                  onsubmit="return confirm('Hapus sertifikat?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada sertifikat diterbitkan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
