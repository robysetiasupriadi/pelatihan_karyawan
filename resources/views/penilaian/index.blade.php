@extends('layouts.app')
@section('title', 'Penilaian')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.show', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Penilaian — {{ $pelatihan->nama }}</h5>
</div>

@if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('pelatihan.penilaian.bulk-update', $pelatihan) }}">
            @csrf
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Peserta</th>
                        <th>Pre Test</th>
                        <th>Post Test</th>
                        <th>Tugas</th>
                        <th>Kehadiran</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilaian as $i => $p)
                    <input type="hidden" name="penilaian[{{ $i }}][id]" value="{{ $p->id }}">
                    <tr>
                        <td class="ps-3 small fw-medium">{{ $p->user->name }}</td>
                        <td><input type="number" name="penilaian[{{ $i }}][nilai_pre_test]"
                            class="form-control form-control-sm" style="width:80px" min="0" max="100" step="0.5"
                            value="{{ $p->nilai_pre_test }}"></td>
                        <td><input type="number" name="penilaian[{{ $i }}][nilai_post_test]"
                            class="form-control form-control-sm" style="width:80px" min="0" max="100" step="0.5"
                            value="{{ $p->nilai_post_test }}"></td>
                        <td><input type="number" name="penilaian[{{ $i }}][nilai_tugas]"
                            class="form-control form-control-sm" style="width:80px" min="0" max="100" step="0.5"
                            value="{{ $p->nilai_tugas }}"></td>
                        <td><input type="number" name="penilaian[{{ $i }}][nilai_kehadiran]"
                            class="form-control form-control-sm" style="width:80px" min="0" max="100" step="0.5"
                            value="{{ $p->nilai_kehadiran }}"></td>
                        <td class="fw-bold">{{ $p->nilai_akhir ?? '-' }}</td>
                        <td>
                            @if($p->grade)
                                <span class="badge bg-{{ in_array($p->grade, ['A','B']) ? 'success' : ($p->grade === 'C' ? 'warning' : 'danger') }}">
                                    {{ $p->grade }}
                                </span>
                            @else -
                            @endif
                        </td>
                        <td>
                            @php $map=['lulus'=>'success','tidak_lulus'=>'danger','belum_dinilai'=>'secondary']; @endphp
                            <span class="badge bg-{{ $map[$p->status_kelulusan] ?? 'secondary' }} small">
                                {{ str_replace('_',' ', $p->status_kelulusan) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data penilaian</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if(count($penilaian) > 0)
            <div class="p-3 border-top">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-save me-1"></i>Simpan Semua Nilai
                </button>
                <span class="text-muted small ms-2">Bobot: Pre 10% · Post 40% · Tugas 30% · Kehadiran 20%</span>
            </div>
            @endif
        </form>
    </div>
</div>
@endif
@endsection
