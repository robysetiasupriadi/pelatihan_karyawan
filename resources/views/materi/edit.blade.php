@extends('layouts.app')
@section('title', 'Edit Materi')
@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('pelatihan.materi.index', $pelatihan) }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Edit Materi — {{ $materi->judul }}</h5>
</div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('pelatihan.materi.update', [$pelatihan, $materi]) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('materi._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('pelatihan.materi.index', $pelatihan) }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
