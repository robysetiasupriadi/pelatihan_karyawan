<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">NIP</label>
        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
            value="{{ old('nip', $user->nip ?? '') }}">
        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">No. Telepon</label>
        <input type="text" name="phone" class="form-control"
            value="{{ old('phone', $user->phone ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Departemen</label>
        <input type="text" name="department" class="form-control"
            value="{{ old('department', $user->department ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Jabatan</label>
        <input type="text" name="position" class="form-control"
            value="{{ old('position', $user->position ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach(['admin','trainer','karyawan'] as $r)
                <option value="{{ $r }}" {{ old('role', $user->role ?? 'karyawan') === $r ? 'selected' : '' }}>
                    {{ ucfirst($r) }}
                </option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @isset($user)
    <div class="col-md-6">
        <label class="form-label fw-medium">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    @endisset
    <div class="col-md-6">
        <label class="form-label fw-medium">
            Password @isset($user)<span class="text-muted small">(kosongkan jika tidak diubah)</span>@endisset
            @unless(isset($user))<span class="text-danger">*</span>@endunless
        </label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            @unless(isset($user)) required @endunless>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
</div>
