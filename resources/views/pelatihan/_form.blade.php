<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-medium">Nama Pelatihan <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $pelatihan->nama ?? '') }}" required>
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}" {{ old('kategori_id', $pelatihan->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-medium">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $pelatihan->deskripsi ?? '') }}</textarea>
    </div>

    {{-- Trainers (many-to-many) --}}
    <div class="col-12">
        <label class="form-label fw-medium">Trainer <span class="text-danger">*</span></label>
        <div id="trainer-list">
            @php
                $selectedTrainers = old('trainer_ids', isset($pelatihan) ? $pelatihan->trainers->pluck('id')->toArray() : []);
                $selectedPerans   = old('trainer_perans', isset($pelatihan) ? $pelatihan->trainers->pluck('pivot.peran')->toArray() : []);
            @endphp
            @if(count($selectedTrainers) > 0)
                @foreach($selectedTrainers as $i => $tid)
                <div class="row g-2 mb-2 trainer-row">
                    <div class="col-md-7">
                        <select name="trainer_ids[]" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Trainer --</option>
                            @foreach($trainers as $t)
                                <option value="{{ $t->id }}" {{ $t->id == $tid ? 'selected' : '' }}>
                                    {{ $t->name }} ({{ $t->department ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="trainer_perans[]" class="form-select form-select-sm">
                            @foreach(['trainer','co-trainer','fasilitator'] as $peran)
                                <option value="{{ $peran }}" {{ ($selectedPerans[$i] ?? 'trainer') === $peran ? 'selected' : '' }}>
                                    {{ ucfirst($peran) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-trainer">✕</button>
                    </div>
                </div>
                @endforeach
            @else
            <div class="row g-2 mb-2 trainer-row">
                <div class="col-md-7">
                    <select name="trainer_ids[]" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Trainer --</option>
                        @foreach($trainers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->department ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="trainer_perans[]" class="form-select form-select-sm">
                        <option value="trainer">Trainer</option>
                        <option value="co-trainer">Co-Trainer</option>
                        <option value="fasilitator">Fasilitator</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-trainer">✕</button>
                </div>
            </div>
            @endif
        </div>
        <button type="button" id="add-trainer" class="btn btn-sm btn-outline-secondary mt-1">
            <i class="bi bi-plus"></i> Tambah Trainer
        </button>
        @error('trainer_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-medium">Tanggal Mulai <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
            value="{{ old('tanggal_mulai', isset($pelatihan) ? $pelatihan->tanggal_mulai->format('Y-m-d') : '') }}" required>
        @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Tanggal Selesai <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
            value="{{ old('tanggal_selesai', isset($pelatihan) ? $pelatihan->tanggal_selesai->format('Y-m-d') : '') }}" required>
        @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control"
            value="{{ old('jam_mulai', $pelatihan->jam_mulai ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control"
            value="{{ old('jam_selesai', $pelatihan->jam_selesai ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-medium">Metode <span class="text-danger">*</span></label>
        <select name="metode" class="form-select" required>
            @foreach(['offline','online','hybrid'] as $m)
                <option value="{{ $m }}" {{ old('metode', $pelatihan->metode ?? 'offline') === $m ? 'selected' : '' }}>
                    {{ ucfirst($m) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Lokasi</label>
        <input type="text" name="lokasi" class="form-control"
            value="{{ old('lokasi', $pelatihan->lokasi ?? '') }}" placeholder="Ruang / Gedung">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Kuota <span class="text-danger">*</span></label>
        <input type="number" name="kuota" class="form-control" min="1"
            value="{{ old('kuota', $pelatihan->kuota ?? 20) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-medium">Link Meeting (Online)</label>
        <input type="url" name="link_meeting" class="form-control"
            value="{{ old('link_meeting', $pelatihan->link_meeting ?? '') }}" placeholder="https://...">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach(['draft','published','ongoing','selesai','dibatalkan'] as $s)
                <option value="{{ $s }}" {{ old('status', $pelatihan->status ?? 'draft') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Biaya (Rp)</label>
        <input type="number" name="biaya" class="form-control" min="0" step="1000"
            value="{{ old('biaya', $pelatihan->biaya ?? 0) }}">
    </div>
</div>

@push('scripts')
<script>
    const trainerTemplate = `
        <div class="row g-2 mb-2 trainer-row">
            <div class="col-md-7">
                <select name="trainer_ids[]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih Trainer --</option>
                    @foreach($trainers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->department ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="trainer_perans[]" class="form-select form-select-sm">
                    <option value="trainer">Trainer</option>
                    <option value="co-trainer">Co-Trainer</option>
                    <option value="fasilitator">Fasilitator</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-trainer">✕</button>
            </div>
        </div>`;

    document.getElementById('add-trainer').addEventListener('click', function() {
        document.getElementById('trainer-list').insertAdjacentHTML('beforeend', trainerTemplate);
    });

    document.getElementById('trainer-list').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-trainer')) {
            const rows = document.querySelectorAll('.trainer-row');
            if (rows.length > 1) e.target.closest('.trainer-row').remove();
        }
    });
</script>
@endpush
