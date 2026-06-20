@extends('layouts.app')
@section('title', 'Rekap Absensi')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.absensi.index', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Rekap Absensi — {{ $pelatihan->nama }}</h5>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Peserta</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-warning">Izin</th>
                        <th class="text-center text-info">Sakit</th>
                        <th class="text-center text-danger">Alpha</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $row)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-medium small">{{ $row['user']->name }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $row['user']->nip }}</div>
                        </td>
                        <td class="text-center">{{ $row['hadir'] }}</td>
                        <td class="text-center">{{ $row['izin'] }}</td>
                        <td class="text-center">{{ $row['sakit'] }}</td>
                        <td class="text-center">{{ $row['alpha'] }}</td>
                        <td class="text-center">{{ $row['total'] }}</td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-1">
                                <div class="progress flex-fill" style="height:6px">
                                    <div class="progress-bar bg-success" style="width:{{ $row['persentase'] }}%"></div>
                                </div>
                                <small>{{ $row['persentase'] }}%</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
