<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Sistem Pelatihan Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            background: #f0f4ff;
        }

        /* ── Sisi Kiri ── */
        .left-panel {
            width: 42%;
            background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            top: -150px; right: -150px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
            bottom: -80px; left: -80px;
        }
        .left-panel .brand-icon {
            width: 72px; height: 72px;
            background: rgba(255,255,255,.2);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.5rem;
        }
        .left-panel h1 { color: #fff; font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: .75rem; }
        .left-panel p  { color: rgba(255,255,255,.8); text-align: center; font-size: .9rem; max-width: 320px; line-height: 1.7; }

        .steps {
            margin-top: 2rem;
            list-style: none;
            padding: 0;
        }
        .steps li {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            color: rgba(255,255,255,.85);
            font-size: .85rem;
            padding: .5rem 0;
        }
        .steps .num {
            width: 26px; height: 26px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; flex-shrink: 0;
        }

        /* ── Sisi Kanan ── */
        .right-panel {
            width: 58%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: #fff;
            overflow-y: auto;
        }

        .register-box { width: 100%; max-width: 480px; }

        .register-box h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: .3rem; }
        .register-box .subtitle { color: #6b7280; font-size: .875rem; margin-bottom: 1.75rem; }

        .form-label {
            font-size: .75rem; font-weight: 600; color: #374151;
            text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;
        }

        .input-wrap { position: relative; }
        .input-wrap .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; font-size: 1rem; pointer-events: none;
        }
        .input-wrap input {
            padding-left: 2.6rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            height: 44px;
            font-size: .875rem;
            transition: all .2s;
        }
        .input-wrap input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .input-wrap input.is-invalid { border-color: #ef4444; }
        .input-wrap .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; cursor: pointer;
            background: none; border: none; font-size: 1rem;
        }

        .section-divider {
            display: flex; align-items: center; gap: .75rem;
            margin: 1.25rem 0 1rem;
        }
        .section-divider span {
            font-size: .7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .07em; color: #9ca3af; white-space: nowrap;
        }
        .section-divider hr { flex: 1; border-color: #e5e7eb; margin: 0; }

        .btn-register {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none; border-radius: 10px;
            height: 46px; font-weight: 600; font-size: .9rem;
            transition: all .2s;
            box-shadow: 0 4px 15px rgba(79,70,229,.3);
        }
        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.4);
        }

        .note-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .8rem;
            color: #15803d;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

{{-- ── Sisi Kiri ── --}}
<div class="left-panel">
    <div class="brand-icon">
        <i class="bi bi-mortarboard-fill text-white fs-3"></i>
    </div>
    <h1>Daftar Sekarang</h1>
    <p>Buat akun karyawan untuk mengakses program pelatihan yang tersedia.</p>

    <ul class="steps">
        <li>
            <span class="num">1</span>
            <div>Isi data diri dan buat password akun Anda</div>
        </li>
        <li>
            <span class="num">2</span>
            <div>Login dan telusuri program pelatihan yang tersedia</div>
        </li>
        <li>
            <span class="num">3</span>
            <div>Daftarkan diri ke pelatihan & tunggu persetujuan</div>
        </li>
        <li>
            <span class="num">4</span>
            <div>Ikuti pelatihan dan dapatkan sertifikat kelulusan</div>
        </li>
    </ul>
</div>

{{-- ── Sisi Kanan ── --}}
<div class="right-panel">
    <div class="register-box">
        <h2>Buat Akun Baru</h2>
        <p class="subtitle">Semua kolom bertanda <span class="text-danger">*</span> wajib diisi</p>

        {{-- Error global --}}
        @if($errors->any())
        <div class="alert d-flex align-items-start gap-2 py-2 mb-3"
             style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:.85rem;color:#dc2626">
            <i class="bi bi-exclamation-circle-fill mt-1"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            {{-- ── Data Pribadi ── --}}
            <div class="section-divider">
                <span>Data Pribadi</span><hr>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Nama sesuai identitas" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">NIP</label>
                    <div class="input-wrap">
                        <i class="bi bi-credit-card input-icon"></i>
                        <input type="text" name="nip"
                            class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip') }}"
                            placeholder="Nomor Induk Pegawai">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <div class="input-wrap">
                        <i class="bi bi-building input-icon"></i>
                        <input type="text" name="department"
                            class="form-control"
                            value="{{ old('department') }}"
                            placeholder="Contoh: IT, Marketing">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Jabatan</label>
                    <div class="input-wrap">
                        <i class="bi bi-briefcase input-icon"></i>
                        <input type="text" name="position"
                            class="form-control"
                            value="{{ old('position') }}"
                            placeholder="Contoh: Staff, Supervisor">
                    </div>
                </div>
            </div>

            {{-- ── Akun & Keamanan ── --}}
            <div class="section-divider">
                <span>Akun & Keamanan</span><hr>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="nama@perusahaan.com" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="pw1"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min. 8 karakter" required>
                        <button type="button" class="toggle-pw" onclick="togglePw('pw1','eye1')">
                            <i class="bi bi-eye" id="eye1"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" id="pw2"
                            class="form-control"
                            placeholder="Ulangi password" required>
                        <button type="button" class="toggle-pw" onclick="togglePw('pw2','eye2')">
                            <i class="bi bi-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            <div class="note-box">
                <i class="bi bi-info-circle me-1"></i>
                Akun baru akan terdaftar sebagai <strong>Karyawan</strong>.
                Untuk role Trainer/Admin, hubungi administrator sistem.
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100 text-white mb-3">
                <i class="bi bi-person-plus me-2"></i>Buat Akun & Masuk
            </button>

            <p class="text-center mb-0" style="font-size:.875rem;color:#6b7280">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:#4f46e5">
                    Masuk di sini
                </a>
            </p>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
</script>
</body>
</html>
