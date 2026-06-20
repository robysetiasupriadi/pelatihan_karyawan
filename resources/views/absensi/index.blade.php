@extends('layouts.app')
@section('title', 'Absensi')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.show', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Absensi — {{ $pelatihan->nama }}</h5>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2 align-items-center">
            <label class="fw-medium small mb-0">Tanggal:</label>
            <input type="date" name="tanggal" class="form-control form-control-sm" style="width:180px"
                value="{{ $tanggal }}">
            <button class="btn btn-sm btn-secondary">Tampilkan</button>
            <a href="{{ route('pelatihan.absensi.rekap', $pelatihan) }}" class="btn btn-sm btn-outline-info ms-auto">
                <i class="bi bi-table me-1"></i>Rekap
            </a>
        </form>
    </div>
</div>

@if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
<form method="POST" action="{{ route('pelatihan.absensi.store', $pelatihan) }}">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Peserta</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $i => $p)
                    <input type="hidden" name="absensi[{{ $i }}][user_id]" value="{{ $p->id }}">
                    <tr>
                        <td class="ps-3">
                            <div class="fw-medium small">{{ $p->name }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $p->nip }}</div>
                        </td>
                        <td>
                            @php $cur = $absensi[$p->id]->status ?? 'alpha'; @endphp
                            <select name="absensi[{{ $i }}][status]" class="form-select form-select-sm" style="width:120px">
                                @foreach(['hadir','izin','sakit','alpha'] as $s)
                                    <option value="{{ $s }}" {{ $cur === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="time" name="absensi[{{ $i }}][jam_masuk]" class="form-control form-control-sm" style="width:110px"
                                value="{{ $absensi[$p->id]->jam_masuk ?? '' }}">
                        </td>
                        <td>
                            <input type="time" name="absensi[{{ $i }}][jam_keluar]" class="form-control form-control-sm" style="width:110px"
                                value="{{ $absensi[$p->id]->jam_keluar ?? '' }}">
                        </td>
                        <td>
                            <input type="text" name="absensi[{{ $i }}][keterangan]" class="form-control form-control-sm"
                                value="{{ $absensi[$p->id]->keterangan ?? '' }}" placeholder="Opsional">
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada peserta yang disetujui</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(count($peserta) > 0)
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save me-1"></i>Simpan Absensi
            </button>
        </div>
        @endif
    </div>
</form>
@endif
@endsection
