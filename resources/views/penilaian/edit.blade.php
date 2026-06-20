@extends('layouts.app')
@section('title', 'Edit Nilai')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.penilaian.index', $pelatihan) }}" class="btn btn-sm btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0">Edit Nilai — {{ $penilaian->user->name }}</h5>
        <div class="text-muted small">{{ $pelatihan->nama }}</div>
    </div>
</div>

<div class="card" style="max-width:540px">
    <div class="card-body">
        <form method="POST" action="{{ route('pelatihan.penilaian.update', [$pelatihan, $penilaian]) }}">
            @csrf @method('PUT')

            <div class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0">
                <div class="small text-muted mb-1">Peserta</div>
                <div class="fw-semibold">{{ $penilaian->user->name }}</div>
                <div class="small text-muted">{{ $penilaian->user->nip }} · {{ $penilaian->user->department }}</div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.8rem">Nilai Pre Test <span class="text-muted">(0–100)</span></label>
                    <input type="number" name="nilai_pre_test" class="form-control @error('nilai_pre_test') is-invalid @enderror"
                        min="0" max="100" step="0.5"
                        value="{{ old('nilai_pre_test', $penilaian->nilai_pre_test) }}">
                    @error('nilai_pre_test')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.8rem">Nilai Post Test <span class="text-muted">(0–100)</span></label>
                    <input type="number" name="nilai_post_test" class="form-control @error('nilai_post_test') is-invalid @enderror"
                        min="0" max="100" step="0.5"
                        value="{{ old('nilai_post_test', $penilaian->nilai_post_test) }}">
                    @error('nilai_post_test')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.8rem">Nilai Tugas <span class="text-muted">(0–100)</span></label>
                    <input type="number" name="nilai_tugas" class="form-control @error('nilai_tugas') is-invalid @enderror"
                        min="0" max="100" step="0.5"
                        value="{{ old('nilai_tugas', $penilaian->nilai_tugas) }}">
                    @error('nilai_tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.8rem">Nilai Kehadiran <span class="text-muted">(0–100)</span></label>
                    <input type="number" name="nilai_kehadiran" class="form-control @error('nilai_kehadiran') is-invalid @enderror"
                        min="0" max="100" step="0.5"
                        value="{{ old('nilai_kehadiran', $penilaian->nilai_kehadiran) }}">
                    @error('nilai_kehadiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium" style="font-size:.8rem">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $penilaian->catatan) }}</textarea>
            </div>

            {{-- Preview formula --}}
            <div class="p-3 rounded mb-3" style="background:#ede9fe;font-size:.78rem">
                <i class="bi bi-info-circle me-1" style="color:#7c3aed"></i>
                <strong>Formula:</strong> Pre(10%) + Post(40%) + Tugas(30%) + Kehadiran(20%)
                <br>Lulus jika nilai akhir ≥ 55
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-4">Simpan & Hitung Otomatis</button>
                <a href="{{ route('pelatihan.penilaian.index', $pelatihan) }}" class="btn btn-light btn-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
