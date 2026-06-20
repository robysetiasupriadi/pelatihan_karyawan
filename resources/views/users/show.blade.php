@extends('layouts.app')
@section('title', 'Detail User')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Detail User</h5>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px">
                    <i class="bi bi-person fs-1 text-secondary"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <div class="text-muted small mb-2">{{ $user->email }}</div>
                <span class="badge role-{{ $user->role }}" style="padding:.35rem .75rem;border-radius:20px;font-size:.8rem">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="card-body border-top">
                <table class="table table-sm table-borderless mb-0 small">
                    <tr><td class="text-muted">NIP</td><td>{{ $user->nip ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $user->phone ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Departemen</td><td>{{ $user->department ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Jabatan</td><td>{{ $user->position ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td>
                            <span class="badge" style="border-radius:6px;font-size:.72rem;background:{{ $user->is_active ? '#dcfce7' : '#f1f5f9' }};color:{{ $user->is_active ? '#15803d' : '#64748b' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Riwayat Pelatihan</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-3">Pelatihan</th><th>Status</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->pelatihanDiikuti as $p)
                        <tr>
                            <td class="ps-3 small">{{ $p->nama }}</td>
                            <td>
                        @php $map=['pending'=>['#fef9c3','#a16207'],'disetujui'=>['#dcfce7','#15803d'],'ditolak'=>['#fee2e2','#dc2626'],'selesai'=>['#f1f5f9','#374151']]; $c=$map[$p->pivot->status]??['#f1f5f9','#374151']; @endphp
                                <span class="badge" style="background:{{ $c[0] }};color:{{ $c[1] }};border-radius:6px;font-size:.72rem">{{ ucfirst($p->pivot->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3 small">Belum ada</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold">Sertifikat</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($user->sertifikat as $s)
                <a href="{{ route('sertifikat.show', $s) }}"
                   class="list-group-item list-group-item-action px-3 py-2 small">
                    <div class="fw-medium">{{ $s->pelatihan->nama ?? '-' }}</div>
                    <div class="text-muted">{{ $s->nomor }} · {{ $s->tanggal_terbit->format('d M Y') }}</div>
                </a>
                @empty
                <div class="list-group-item text-center text-muted py-3 small">Belum ada sertifikat</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
