<div class="mb-3">
    <label class="form-label fw-medium">Judul Materi <span class="text-danger">*</span></label>
    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
        value="{{ old('judul', $materi->judul ?? '') }}" required>
    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-medium">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $materi->deskripsi ?? '') }}</textarea>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-medium">Urutan <span class="text-danger">*</span></label>
        <input type="number" name="urutan" class="form-control" min="0"
            value="{{ old('urutan', $materi->urutan ?? 0) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Durasi (menit)</label>
        <input type="number" name="durasi_menit" class="form-control" min="1"
            value="{{ old('durasi_menit', $materi->durasi_menit ?? '') }}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-medium">Link Video (YouTube/Google Meet)</label>
    <input type="url" name="link_video" class="form-control"
        value="{{ old('link_video', $materi->link_video ?? '') }}" placeholder="https://...">
</div>
<div class="mb-3">
    <label class="form-label fw-medium">File Materi</label>
    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xlsx,.zip">
    @isset($materi->file)
        <div class="form-text">File saat ini: <code>{{ basename($materi->file) }}</code></div>
    @endisset
    @error('file')<div class="text-danger small">{{ $message }}</div>@enderror
</div>
