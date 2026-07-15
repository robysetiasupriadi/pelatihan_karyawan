<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi — APEX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            top: -150px; right: -150px;
        }
        body::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
            bottom: -80px; left: -80px;
        }

        .card-box {
            background: #fff;
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            padding: 2.75rem 2.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            position: relative;
            z-index: 1;
        }

        .icon-circle {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .icon-circle i { color: #fff; font-size: 1.6rem; }

        .card-box h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: .5rem;
        }

        .card-box .subtitle {
            text-align: center;
            color: #6b7280;
            font-size: .9rem;
            margin-bottom: 1.75rem;
            line-height: 1.6;
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

        .btn-send {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 10px;
            height: 48px;
            font-weight: 600;
            font-size: .95rem;
            transition: all .2s;
            box-shadow: 0 4px 15px rgba(79,70,229,.35);
        }
        .btn-send:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.45);
        }

        .back-link {
            font-size: .85rem;
            font-weight: 600;
            color: #4f46e5;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; color: #4338ca; }
    </style>
</head>
<body>

<div class="card-box">
    <div class="icon-circle">
        <i class="bi bi-key-fill"></i>
    </div>

    <h2>Atur Ulang Kata Sandi</h2>
    <p class="subtitle">
        Buat kata sandi baru untuk akun Anda.
    </p>

    {{-- Error --}}
    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:.875rem">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="text-start">
        @csrf

        {{-- Token dari email --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $email ?? '') }}"
                    placeholder="nama@perusahaan.com" required autofocus>
            </div>
        </div>

        {{-- Password Baru --}}
        <div class="mb-3">
            <label class="form-label">Kata Sandi Baru</label>
            <div class="input-wrap">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="passwordInput"
                    class="form-control" placeholder="••••••••" required>
                <button type="button" class="toggle-pw" onclick="togglePassword('passwordInput','eyeIcon1')">
                    <i class="bi bi-eye" id="eyeIcon1"></i>
                </button>
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label class="form-label">Konfirmasi Kata Sandi</label>
            <div class="input-wrap">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password_confirmation" id="passwordConfirmInput"
                    class="form-control" placeholder="••••••••" required>
                <button type="button" class="toggle-pw" onclick="togglePassword('passwordConfirmInput','eyeIcon2')">
                    <i class="bi bi-eye" id="eyeIcon2"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-send w-100 text-white mb-3">
            Simpan Kata Sandi Baru
        </button>
    </form>

    <p class="text-center mt-2 mb-0">
        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
    </p>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
</body>
</html>
