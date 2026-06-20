@extends('layouts.app')
@section('title', 'Dashboard Trainer')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe">
                <i class="bi bi-journal-bookmark-fill" style="color:#2563eb"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_pelatihan'] }}</div>
                <div class="stat-label">Total Pelatihan Saya</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">
                <i class="bi bi-play-circle-fill" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['pelatihan_aktif'] }}</div>
                <div class="stat-label">Sedang Berjalan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe">
                <i class="bi bi-people-fill" style="color:#7c3aed"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total_peserta'] }}</div>
                <div class="stat-label">Total Peserta</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="fw-semibold" style="font-size:.9rem">
            <i class="bi bi-journal-bookmark me-2" style="color:var(--accent)"></i>Pelatihan yang Saya Ajar
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-3">Pelatihan</th>
                    <th>Metode</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelatihan_saya as $p)
                <tr>
                    <td class="ps-3">
                        <div class="fw-medium" style="font-size:.85rem">{{ $p->nama }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $p->kode }}</div>
                    </td>
                    <td><span class="badge" style="background:#f1f5f9;color:#475569;border-radius:6px;font-size:.72rem">{{ $p->metode }}</span></td>
                    <td style="font-size:.85rem">{{ $p->peserta_count }}</td>
                    <td><span class="badge status-{{ $p->status }}" style="border-radius:6px;font-size:.72rem">{{ ucfirst($p->status) }}</span></td>
                    <td class="text-muted" style="font-size:.8rem">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('pelatihan.show', $p) }}"
                           class="btn btn-sm" style="background:#f1f5f9;font-size:.75rem;border-radius:8px">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4" style="font-size:.85rem">Belum ada pelatihan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
