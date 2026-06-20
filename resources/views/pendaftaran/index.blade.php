@extends('layouts.app')
@section('title', 'Manajemen Pendaftaran')

@section('content')
<h5 class="fw-bold mb-3">Manajemen Pendaftaran</h5>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['pending','disetujui','ditolak','selesai'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('pendaftaran.bulk-proses') }}" id="bulk-form">
            @csrf
            <div class="p-3 border-bottom d-flex gap-2 align-items-center">
                <button type="submit" name="status" value="disetujui" class="btn btn-sm btn-success">
                    <i class="bi bi-check-lg me-1"></i><span id="label-setujui">Setujui Semua Terpilih</span>
                </button>
                <button type="submit" name="status" value="ditolak" class="btn btn-sm btn-danger">
                    <i class="bi bi-x-lg me-1"></i><span id="label-tolak">Tolak Semua Terpilih</span>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th>Karyawan</th>
                            <th>Pelatihan</th>
                            <th>Tgl Daftar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaran as $d)
                        <tr>
                            <td class="ps-3">
                                <input type="checkbox" name="ids[]" value="{{ $d->id }}" class="form-check-input check-item">
                            </td>
                            <td>
                                <div class="fw-medium">{{ $d->user->name }}</div>
                                <div class="small text-muted">{{ $d->user->nip }} · {{ $d->user->department }}</div>
                            </td>
                            <td>
                                <a href="{{ route('pelatihan.show', $d->pelatihan) }}" class="text-decoration-none">
                                    {{ $d->pelatihan->nama }}
                                </a>
                            </td>
                            <td class="small text-muted">{{ $d->tanggal_daftar?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                @php
                                    $map = [
                                        'pending'   => ['#fef9c3','#a16207'],
                                        'disetujui' => ['#dcfce7','#15803d'],
                                        'ditolak'   => ['#fee2e2','#dc2626'],
                                        'selesai'   => ['#f1f5f9','#374151'],
                                    ];
                                    $c = $map[$d->status] ?? ['#f1f5f9','#374151'];
                                @endphp
                                <span class="badge" style="background:{{ $c[0] }};color:{{ $c[1] }};border-radius:6px;font-size:.72rem">
                                    {{ ucfirst($d->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<div class="mt-3">{{ $pendaftaran->links() }}</div>
@endsection

@push('scripts')
<script>
    const checkAll     = document.getElementById('check-all');
    const labelSetujui = document.getElementById('label-setujui');
    const labelTolak   = document.getElementById('label-tolak');

    function updateLabels() {
        const count = document.querySelectorAll('.check-item:checked').length;
        if (count <= 1) {
            labelSetujui.textContent = 'Setujui';
            labelTolak.textContent   = 'Tolak';
        } else {
            labelSetujui.textContent = 'Setujui Semua Terpilih';
            labelTolak.textContent   = 'Tolak Semua Terpilih';
        }
    }

    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.check-item').forEach(c => c.checked = this.checked);
        updateLabels();
    });

    document.querySelectorAll('.check-item').forEach(c => {
        c.addEventListener('change', updateLabels);
    });

    document.getElementById('bulk-form').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.check-item:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu pendaftaran terlebih dahulu.');
        }
    });
</script>
@endpush