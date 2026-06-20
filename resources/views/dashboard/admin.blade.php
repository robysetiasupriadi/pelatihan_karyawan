@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe">
                <i class="bi bi-people-fill" style="color:#7c3aed"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_karyawan'] }}</div>
                <div class="stat-label">Karyawan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="bi bi-person-workspace" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_trainer'] }}</div>
                <div class="stat-label">Trainer</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe">
                <i class="bi bi-journal-bookmark-fill" style="color:#2563eb"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_pelatihan'] }}</div>
                <div class="stat-label">Pelatihan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <i class="bi bi-tags-fill" style="color:#ca8a04"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_kategori'] }}</div>
                <div class="stat-label">Kategori</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2">
                <i class="bi bi-hourglass-split" style="color:#dc2626"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['pending_daftar'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5">
                <i class="bi bi-play-circle-fill" style="color:#059669"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['pelatihan_aktif'] }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabel + Pending ── --}}
<div class="row g-3">

    {{-- Pelatihan Terbaru --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold" style="font-size:.9rem">
                    <i class="bi bi-journal-bookmark me-2" style="color:var(--accent)"></i>Pelatihan Terbaru
                </span>
                <a href="{{ route('pelatihan.index') }}" class="btn btn-sm"
                   style="background:#f1f5f9;font-size:.78rem;border-radius:8px">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Nama Pelatihan</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelatihan_terbaru as $p)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('pelatihan.show', $p) }}" class="text-decoration-none fw-medium text-dark">
                                    {{ $p->nama }}
                                </a>
                                <div class="text-muted" style="font-size:.72rem">{{ $p->kode }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#f1f5f9;color:#475569;border-radius:6px;font-size:.72rem">
                                    {{ $p->metode }}
                                </span>
                            </td>
                            <td>
                                <span class="badge status-{{ $p->status }}" style="border-radius:6px;font-size:.72rem">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:.8rem">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4" style="font-size:.85rem">
                                Belum ada pelatihan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pendaftaran Pending --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold" style="font-size:.9rem">
                    <i class="bi bi-person-check me-2" style="color:#f59e0b"></i>Pendaftaran Pending
                </span>
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-sm"
                   style="background:#fef9c3;color:#a16207;font-size:.78rem;border-radius:8px">Kelola</a>
            </div>
            <div class="card-body p-0">
                @forelse($pendaftaran_pending as $d)
                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                     style="border-bottom:1px solid #f8fafc">
                    <div>
                        <div class="fw-medium" style="font-size:.83rem">{{ $d->user->name }}</div>
                        <div class="text-muted" style="font-size:.73rem">{{ Str::limit($d->pelatihan->nama, 35) }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <form method="POST" action="{{ route('pendaftaran.proses', $d) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="disetujui">
                            <button class="btn btn-sm d-flex align-items-center justify-content-center"
                                    style="width:28px;height:28px;background:#dcfce7;color:#16a34a;border-radius:8px;padding:0"
                                    title="Setujui">
                                <i class="bi bi-check-lg" style="font-size:.85rem"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('pendaftaran.proses', $d) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button class="btn btn-sm d-flex align-items-center justify-content-center"
                                    style="width:28px;height:28px;background:#fee2e2;color:#dc2626;border-radius:8px;padding:0"
                                    title="Tolak">
                                <i class="bi bi-x-lg" style="font-size:.85rem"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                    <i class="bi bi-check-circle fs-2 mb-2 text-success"></i>
                    <span style="font-size:.83rem">Semua pendaftaran sudah diproses</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
