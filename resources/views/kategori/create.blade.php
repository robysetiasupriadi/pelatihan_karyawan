@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Tambah Kategori</h5>
</div>
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="{{ route('kategori.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
