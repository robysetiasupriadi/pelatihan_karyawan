@extends('layouts.app')
@section('title', 'Tambah Pelatihan')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Tambah Pelatihan Baru</h5>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pelatihan.store') }}">
            @csrf
            @include('pelatihan._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pelatihan.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
