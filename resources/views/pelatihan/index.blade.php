@extends('layouts.app')
@section('title', 'Daftar Pelatihan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Daftar Pelatihan</h5>
    @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
        <a href="{{ route('pelatihan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Pelatihan
        </a>
    @endif
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / kode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['draft','published','ongoing','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="metode" class="form-select form-select-sm">
                    <option value="">Semua Metode</option>
                    @foreach(['online','offline','hybrid'] as $m)
                        <option value="{{ $m }}" {{ request('metode') == $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($pelatihan as $p)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    @php $badgeMap = ['draft'=>'secondary','published'=>'primary','ongoing'=>'success','selesai'=>'dark','dibatalkan'=>'danger']; @endphp
                    <span class="badge bg-{{ $badgeMap[$p->status] ?? 'secondary' }}">{{ $p->status }}</span>
                    <span class="badge bg-light text-dark">{{ $p->metode }}</span>
                </div>
                <h6 class="fw-semibold mb-1">
                    <a href="{{ route('pelatihan.show', $p) }}" class="text-decoration-none text-dark">{{ $p->nama }}</a>
                </h6>
                <div class="small text-muted mb-2">{{ $p->kode }} · {{ $p->kategori->nama ?? '-' }}</div>
                <p class="small text-muted mb-3">{{ Str::limit($p->deskripsi, 80) }}</p>
                <div class="d-flex justify-content-between small text-muted">
                    <span><i class="bi bi-calendar me-1"></i>{{ $p->tanggal_mulai->format('d M Y') }}</span>
                    <span><i class="bi bi-people me-1"></i>{{ $p->peserta_count }}/{{ $p->kuota }}</span>
                </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('pelatihan.show', $p) }}" class="btn btn-sm btn-outline-primary flex-fill">Detail</a>
                @if(auth()->user()->isKaryawan() && $p->status === 'published')
                    <form method="POST" action="{{ route('pendaftaran.daftar', $p) }}">
                        @csrf
                        <button class="btn btn-sm btn-primary">Daftar</button>
                    </form>
                @endif
                @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
                    <a href="{{ route('pelatihan.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('pelatihan.destroy', $p) }}"
                          onsubmit="return confirm('Yakin ingin menghapus pelatihan \"{{ addslashes($p->nama) }}\"?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card"><div class="card-body text-center text-muted py-5">
            <i class="bi bi-journal-x fs-1 d-block mb-2"></i>Belum ada pelatihan
        </div></div>
    </div>
    @endforelse
</div>

<div class="mt-3">{{ $pelatihan->links() }}</div>
@endsection