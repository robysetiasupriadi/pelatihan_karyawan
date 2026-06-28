<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — APEX</title>
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

        /* ── Sisi Kiri: Ilustrasi & Branding ── */
        .left-panel {
            width: 50%;
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
            animation: floatBlob1 9s ease-in-out infinite;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
            bottom: -80px; left: -80px;
            animation: floatBlob2 11s ease-in-out infinite;
        }

        @keyframes floatBlob1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(-30px, 40px) scale(1.1); }
        }

        @keyframes floatBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(25px, -35px) scale(1.15); }
        }

        /* ── Brand icon: wrapper + kotak border ── */
        .brand-icon-wrap {
            position: relative;
            width: 130px;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            z-index: 1;
        }

        .square-border {
            position: absolute;
            width: 130px;
            height: 130px;
            border: 1.5px solid rgba(255,255,255,0.35);
            border-radius: 22px;
        }

        .left-panel .brand-icon {
            width: 80px; height: 80px;
            background: rgba(255,255,255,.2);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
            position: relative;
            animation: iconFloat 3.5s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }

        .brand-icon .badge-cert {
            position: absolute;
            bottom: -8px;
            right: -8px;
            width: 34px;
            height: 34px;
            background: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-panel h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: .25rem;
            position: relative;
            z-index: 1;
        }

        .left-panel .brand-subtitle {
            color: rgba(255,255,255,.75);
            text-align: center;
            font-size: .8rem;
            font-weight: 500;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .left-panel p {
            color: rgba(255,255,255,.8);
            text-align: center;
            font-size: .95rem;
            max-width: 360px;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .feature-list {
            list-style: none;
            padding: 0; margin: 2rem 0 0;
            position: relative;
            z-index: 1;
        }
        .feature-list li {
            color: rgba(255,255,255,.85);
            font-size: .875rem;
            padding: .4rem 0;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .feature-list li .icon {
            width: 28px; height: 28px;
            background: rgba(255,255,255,.15);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
            flex-shrink: 0;
        }

        /* ── Sisi Kanan: Form Login ── */
        .right-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            background: #fff;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
        }

        .login-box h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
            line-height: 1.2;
            margin-bottom: .75rem;
        }

        .login-box .subtitle {
            text-align: center;
            color: #6b7280;
            font-size: .95rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .4rem;
        }

        .input-wrap { position: relative; }
        .input-wrap .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; font-size: 1rem;
        }
        .input-wrap input {
            padding-left: 2.6rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            height: 48px;
            font-size: .9rem;
            transition: all .2s;
        }
        .input-wrap input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .input-wrap .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; cursor: pointer; font-size: 1rem;
            background: none; border: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 10px;
            height: 48px;
            font-weight: 600;
            font-size: .95rem;
            letter-spacing: .02em;
            transition: all .2s;
            box-shadow: 0 4px 15px rgba(79,70,229,.35);
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.45);
        }

        .demo-accounts {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-top: 1.5rem;
        }
        .demo-accounts .title {
            font-size: .75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .6rem;
        }
        .demo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .3rem 0;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
        }
        .demo-item:last-child { border: none; }
        .demo-item:hover .demo-email { color: #4f46e5; }
        .demo-badge {
            font-size: .7rem;
            padding: .15rem .5rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .demo-email { font-size: .78rem; color: #374151; transition: color .2s; }
        .demo-pass  { font-size: .72rem; color: #9ca3af; }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; }
        }
    </style>
</head>
<body>

{{-- ── Sisi Kiri ── --}}
<div class="left-panel">
    <div class="brand-icon-wrap">
        {{-- Kotak border statis, ganti dashed ring yang berputar --}}
        <div class="square-border"></div>
        <div class="brand-icon">
            <i class="bi bi-people-fill text-white fs-3"></i>
            <div class="badge-cert">
                <i class="bi bi-patch-check-fill" style="color:#4f46e5;font-size:1rem"></i>
            </div>
        </div>
    </div>
    <h1>APEX</h1>
    <div class="brand-subtitle">Application &amp; Process Excellence</div>
    <p>Platform digital untuk mengelola program pelatihan, absensi, penilaian, dan sertifikasi karyawan secara terpusat.</p>

    <ul class="feature-list">
        <li>
            <span class="icon"><i class="bi bi-journal-bookmark-fill"></i></span>
            Manajemen Program Pelatihan
        </li>
        <li>
            <span class="icon"><i class="bi bi-person-check-fill"></i></span>
            Pendaftaran & Persetujuan Peserta
        </li>
        <li>
            <span class="icon"><i class="bi bi-check2-square"></i></span>
            Absensi & Rekap Kehadiran
        </li>
        <li>
            <span class="icon"><i class="bi bi-bar-chart-fill"></i></span>
            Penilaian & Evaluasi Otomatis
        </li>
        <li>
            <span class="icon"><i class="bi bi-award-fill"></i></span>
            Penerbitan Sertifikat Digital
        </li>
    </ul>
</div>

{{-- ── Sisi Kanan ── --}}
<div class="right-panel">
    <div class="login-box">
        <h2>
            Selamat Datang Di<br>
            APEX
        </h2>

        <p class="subtitle">
            Masuk untuk mengakses dashboard pelatihan Anda
        </p>

        {{-- Error --}}
        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:.875rem">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="text-start">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email') }}"
                        placeholder="nama@perusahaan.com" required autofocus>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="passwordInput"
                        class="form-control" placeholder="••••••••" required>
                    <button type="button" class="toggle-pw" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember"
                           style="border-color:#d1d5db;border-radius:4px">
                    <label class="form-check-label" for="remember" style="font-size:.875rem;color:#374151">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                Masuk ke Dashboard
            </button>
        </form>

        {{-- Demo Accounts --}}
        <div class="demo-accounts">
            <div class="title">⚡ Klik untuk isi otomatis</div>

            <div class="demo-item" onclick="fillLogin('admin@pelatihan.test')">
                <div>
                    <span class="demo-badge bg-primary text-white me-2">Admin</span>
                    <span class="demo-email">admin@pelatihan.test</span>
                </div>
                <span class="demo-pass">password</span>
            </div>

            <div class="demo-item" onclick="fillLogin('trainer@pelatihan.test')">
                <div>
                    <span class="demo-badge bg-success text-white me-2">Trainer</span>
                    <span class="demo-email">trainer@pelatihan.test</span>
                </div>
                <span class="demo-pass">password</span>
            </div>

            <div class="demo-item" onclick="fillLogin('karyawan@pelatihan.test')">
                <div>
                    <span class="demo-badge bg-warning text-dark me-2">Karyawan</span>
                    <span class="demo-email">karyawan@pelatihan.test</span>
                </div>
                <span class="demo-pass">password</span>
            </div>
        </div>

        <p class="text-center text-muted mt-4 mb-0" style="font-size:.75rem">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color:#4f46e5">Daftar sekarang</a>
        </p>

        <p class="text-center text-muted mt-2 mb-0" style="font-size:.75rem">
            © {{ date('Y') }} APEX
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    function fillLogin(email) {
        document.querySelector('input[name="email"]').value = email;
        document.getElementById('passwordInput').value = 'password';
        document.querySelector('input[name="email"]').focus();
    }
</script>
</body>
</html>