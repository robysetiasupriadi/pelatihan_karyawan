@extends('layouts.app')
@section('title', $pelatihan->nama)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">{{ $pelatihan->nama }}</h5>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    @php $badgeMap = ['draft'=>'secondary','published'=>'primary','ongoing'=>'success','selesai'=>'dark','dibatalkan'=>'danger']; @endphp
                    <span class="badge bg-{{ $badgeMap[$pelatihan->status] ?? 'secondary' }}">{{ $pelatihan->status }}</span>
                    <span class="badge bg-light text-dark">{{ $pelatihan->metode }}</span>
                    <span class="badge bg-info text-dark">{{ $pelatihan->kategori->nama ?? '-' }}</span>
                </div>
                <p class="text-muted">{{ $pelatihan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                <div class="row g-2 small">
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-2">
                            <div class="text-muted">Tanggal</div>
                            <div class="fw-medium">
                                {{ $pelatihan->tanggal_mulai->format('d M Y') }} –
                                {{ $pelatihan->tanggal_selesai->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-2">
                            <div class="text-muted">Jam</div>
                            <div class="fw-medium">
                                {{ $pelatihan->jam_mulai ?? '-' }} – {{ $pelatihan->jam_selesai ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-2">
                            <div class="text-muted">Lokasi</div>
                            <div class="fw-medium">{{ $pelatihan->lokasi ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-2">
                            <div class="text-muted">Kuota</div>
                            <div class="fw-medium">{{ $pelatihan->peserta_count }}/{{ $pelatihan->kuota }} peserta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Materi --}}
        <div class="card mb-3">
            <div class="card-header bg-white d-flex justify-content-between py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-text me-2"></i>Materi Pelatihan</h6>
                @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
                    <a href="{{ route('pelatihan.materi.create', $pelatihan) }}" class="btn btn-sm btn-outline-primary">+ Tambah</a>
                @endif
            </div>
            <div class="list-group list-group-flush">
                @forelse($pelatihan->materi as $materi)
                <a href="{{ route('pelatihan.materi.show', [$pelatihan, $materi]) }}"
                   class="list-group-item list-group-item-action px-3 py-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary">{{ $materi->urutan }}</span>
                        <div>
                            <div class="fw-medium small">{{ $materi->judul }}</div>
                            @if($materi->durasi_menit)
                            <div class="text-muted" style="font-size:.75rem"><i class="bi bi-clock me-1"></i>{{ $materi->durasi_menit }} menit</div>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="list-group-item text-center text-muted py-3 small">Belum ada materi</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Trainer --}}
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person-workspace me-2"></i>Trainer</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($pelatihan->trainers as $t)
                <div class="list-group-item px-3 py-2">
                    <div class="fw-medium small">{{ $t->name }}</div>
                    <div class="text-muted" style="font-size:.75rem">
                        {{ $t->pivot->peran }} · {{ $t->department ?? '-' }}
                    </div>
                </div>
                @empty
                <div class="list-group-item text-muted small py-3 text-center">Belum ada trainer</div>
                @endforelse
            </div>
        </div>

        {{-- Aksi --}}
        <div class="card">
            <div class="card-body d-grid gap-2">

                {{-- Ubah Status (admin only) --}}
                @if(auth()->user()->isAdmin())
                <div>
                    <label class="form-label small fw-medium mb-1">
                        <i class="bi bi-arrow-repeat me-1"></i>Ubah Status
                    </label>
                    <form method="POST" action="{{ route('pelatihan.updateStatus', $pelatihan) }}" class="d-flex gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select form-select-sm">
                            @foreach(['draft','published','ongoing','selesai','dibatalkan'] as $s)
                                <option value="{{ $s }}" {{ $pelatihan->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary text-nowrap">Simpan</button>
                    </form>
                    @if(session('success'))
                        <div class="text-success small mt-1">{{ session('success') }}</div>
                    @endif
                </div>
                <hr class="my-1">
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
                    <a href="{{ route('pelatihan.absensi.index', $pelatihan) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-check2-square me-1"></i>Absensi
                    </a>
                    <a href="{{ route('pelatihan.penilaian.index', $pelatihan) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-bar-chart me-1"></i>Penilaian
                    </a>
                    <a href="{{ route('pelatihan.absensi.rekap', $pelatihan) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-table me-1"></i>Rekap Absensi
                    </a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('pelatihan.sertifikat.index', $pelatihan) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-award me-1"></i>Sertifikat
                    </a>
                    <a href="{{ route('pelatihan.edit', $pelatihan) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Pelatihan
                    </a>
                    <form method="POST" action="{{ route('pelatihan.destroy', $pelatihan) }}"
                          onsubmit="return confirm('Hapus pelatihan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
                @endif
                @if(auth()->user()->isKaryawan() && $pelatihan->status === 'published')
                    <form method="POST" action="{{ route('pendaftaran.daftar', $pelatihan) }}">
                        @csrf
                        <button class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection