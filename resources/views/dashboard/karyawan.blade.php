@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe">
                <i class="bi bi-journal-check" style="color:#2563eb"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_pelatihan'] }}</div>
                <div class="stat-label">Pelatihan Diikuti</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="bi bi-patch-check-fill" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['pelatihan_selesai'] }}</div>
                <div class="stat-label">Pelatihan Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <i class="bi bi-award-fill" style="color:#ca8a04"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_sertifikat'] }}</div>
                <div class="stat-label">Sertifikat Diperoleh</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Pelatihan saya --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <span class="fw-semibold" style="font-size:.9rem">
                    <i class="bi bi-journal-check me-2" style="color:var(--accent)"></i>Pelatihan Saya
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Pelatihan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelatihan_diikuti as $p)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('pelatihan.show', $p) }}" class="text-decoration-none fw-medium text-dark" style="font-size:.85rem">
                                    {{ $p->nama }}
                                </a>
                            </td>
                            <td>
                                @php $map = ['pending'=>['#fef9c3','#a16207'],'disetujui'=>['#dcfce7','#15803d'],'ditolak'=>['#fee2e2','#dc2626'],'selesai'=>['#f1f5f9','#374151']]; $c = $map[$p->pivot->status] ?? ['#f1f5f9','#374151']; @endphp
                                <span class="badge" style="background:{{ $c[0] }};color:{{ $c[1] }};border-radius:6px;font-size:.72rem">
                                    {{ ucfirst($p->pivot->status) }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:.8rem">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:.85rem">Belum mengikuti pelatihan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pelatihan tersedia --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold" style="font-size:.9rem">
                    <i class="bi bi-search me-2" style="color:#16a34a"></i>Pelatihan Tersedia
                </span>
                <a href="{{ route('pelatihan.index') }}" class="btn btn-sm"
                   style="background:#dcfce7;color:#15803d;font-size:.78rem;border-radius:8px">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($pelatihan_tersedia as $p)
                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                     style="border-bottom:1px solid #f8fafc">
                    <div>
                        <div class="fw-medium" style="font-size:.83rem">{{ $p->nama }}</div>
                        <div class="text-muted" style="font-size:.73rem">
                            <i class="bi bi-geo-alt me-1"></i>{{ ucfirst($p->metode) }}
                            <span class="mx-1">·</span>
                            <i class="bi bi-calendar me-1"></i>{{ $p->tanggal_mulai->format('d M Y') }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('pendaftaran.daftar', $p) }}">
                        @csrf
                        <button class="btn btn-sm"
                                style="background:var(--accent);color:#fff;border-radius:8px;font-size:.75rem;padding:.25rem .75rem">
                            Daftar
                        </button>
                    </form>
                </div>
                @empty
                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-2 mb-2"></i>
                    <span style="font-size:.83rem">Tidak ada pelatihan tersedia</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
