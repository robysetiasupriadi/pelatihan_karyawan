@extends('layouts.app')
@section('title', $materi->judul)
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.materi.index', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">{{ $materi->judul }}</h5>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                @if($materi->link_video)
                <div class="ratio ratio-16x9 mb-3">
                    <iframe src="{{ $materi->link_video }}" allowfullscreen></iframe>
                </div>
                @endif

                <p class="text-muted">{{ $materi->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                @if($materi->file)
                <a href="{{ Storage::url($materi->file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i>Download Materi
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Info Materi</h6>
                <table class="table table-sm table-borderless small mb-3">
                    <tr><td class="text-muted">Urutan</td><td>{{ $materi->urutan }}</td></tr>
                    <tr><td class="text-muted">Durasi</td><td>{{ $materi->durasi_menit ? $materi->durasi_menit.' menit' : '-' }}</td></tr>
                </table>

                @if(auth()->user()->isKaryawan())
                    @if($progress && $progress->selesai)
                        <div class="alert alert-success py-2 small mb-0">
                            <i class="bi bi-check-circle me-1"></i>Materi sudah diselesaikan
                        </div>
                    @else
                        <button id="btn-selesai" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check2 me-1"></i>Tandai Selesai
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const btn = document.getElementById('btn-selesai');
    if (btn) {
        btn.addEventListener('click', function() {
            fetch('{{ route("pelatihan.materi.selesaikan", [$pelatihan, $materi]) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    btn.outerHTML = '<div class="alert alert-success py-2 small mb-0"><i class="bi bi-check-circle me-1"></i>Materi sudah diselesaikan</div>';
                }
            });
        });
    }
</script>
@endpush
