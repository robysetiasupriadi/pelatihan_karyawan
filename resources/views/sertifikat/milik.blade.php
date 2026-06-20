@extends('layouts.app')
@section('title', 'Sertifikat Saya')
@section('content')
<h5 class="fw-bold mb-3">Sertifikat Saya</h5>

<div class="row g-3">
    @forelse($sertifikat as $s)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:64px;height:64px">
                    <i class="bi bi-award-fill text-warning fs-2"></i>
                </div>
                <h6 class="fw-bold mb-1">{{ $s->pelatihan->nama ?? '-' }}</h6>
                <div class="small text-muted mb-3">
                    <code>{{ $s->nomor }}</code>
                </div>
                <div class="small text-muted mb-3">
                    <i class="bi bi-calendar me-1"></i>{{ $s->tanggal_terbit->format('d M Y') }}
                </div>
                <a href="{{ route('sertifikat.show', $s) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-eye me-1"></i>Lihat Sertifikat
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-award fs-1 d-block mb-2"></i>
                Belum ada sertifikat. Selesaikan pelatihan untuk mendapatkan sertifikat.
            </div>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $sertifikat->links() }}</div>
@endsection
