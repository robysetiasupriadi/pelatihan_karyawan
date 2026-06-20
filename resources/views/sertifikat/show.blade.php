@extends('layouts.app')
@section('title', 'Sertifikat')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="javascript:history.back()" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Sertifikat Pelatihan</h5>
</div>

<div class="card" style="max-width:700px; margin:auto">
    <div class="card-body text-center p-5" style="border: 3px solid #f59e0b; border-radius: 1rem;">
        <div class="text-warning mb-2"><i class="bi bi-award-fill" style="font-size:3rem"></i></div>
        <div class="text-muted small mb-1">SERTIFIKAT KELULUSAN</div>
        <h2 class="fw-bold mb-1">{{ $sertifikat->user->name }}</h2>
        <div class="text-muted mb-3">{{ $sertifikat->user->nip }} · {{ $sertifikat->user->department }}</div>

        <p class="mb-1">Telah berhasil menyelesaikan pelatihan</p>
        <h4 class="fw-bold text-primary mb-1">{{ $sertifikat->pelatihan->nama }}</h4>
        <div class="text-muted small mb-4">
            {{ $sertifikat->pelatihan->tanggal_mulai->format('d M Y') }} –
            {{ $sertifikat->pelatihan->tanggal_selesai->format('d M Y') }}
        </div>

        <div class="row justify-content-center mb-4">
            @foreach($sertifikat->pelatihan->trainers as $t)
            <div class="col-auto text-center px-4">
                <div class="border-top border-dark pt-1 mt-4" style="min-width:140px">
                    <div class="fw-semibold small">{{ $t->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $t->pivot->peran }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-muted small">
            No: <code>{{ $sertifikat->nomor }}</code> · Diterbitkan: {{ $sertifikat->tanggal_terbit->format('d M Y') }}
        </div>
    </div>
</div>

<div class="text-center mt-3">
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i>Cetak
    </button>
</div>
@endsection
