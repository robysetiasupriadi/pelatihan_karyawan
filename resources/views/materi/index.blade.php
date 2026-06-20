@extends('layouts.app')
@section('title', 'Materi Pelatihan')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.show', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0">Materi: {{ $pelatihan->nama }}</h5>
        <div class="small text-muted">{{ $pelatihan->kode }}</div>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
    <a href="{{ route('pelatihan.materi.create', $pelatihan) }}" class="btn btn-primary btn-sm ms-auto">
        <i class="bi bi-plus-lg me-1"></i>Tambah Materi
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($pelatihan->materi as $m)
            <div class="list-group-item px-3 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-secondary fs-6">{{ $m->urutan }}</span>
                        <div>
                            <div class="fw-medium">{{ $m->judul }}</div>
                            <div class="small text-muted">
                                @if($m->durasi_menit)<i class="bi bi-clock me-1"></i>{{ $m->durasi_menit }} menit @endif
                                @if($m->file)<i class="bi bi-paperclip ms-2 me-1"></i>File @endif
                                @if($m->link_video)<i class="bi bi-play-circle ms-2 me-1"></i>Video @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('pelatihan.materi.show', [$pelatihan, $m]) }}" class="btn btn-sm btn-outline-primary py-0">Lihat</a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
                        <a href="{{ route('pelatihan.materi.edit', [$pelatihan, $m]) }}" class="btn btn-sm btn-outline-secondary py-0">Edit</a>
                        <form method="POST" action="{{ route('pelatihan.materi.destroy', [$pelatihan, $m]) }}"
                              onsubmit="return confirm('Hapus materi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="list-group-item text-center text-muted py-5">
                <i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>Belum ada materi
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
