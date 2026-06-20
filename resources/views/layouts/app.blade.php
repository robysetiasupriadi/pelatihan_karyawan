<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Silatih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --topbar-h: 60px;
            --accent: #4f46e5;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; }

        /* ════════ SIDEBAR ════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
        }
        .sidebar-brand .logo {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand .app-name {
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            line-height: 1.3;
        }
        .sidebar-brand .app-sub {
            color: rgba(255,255,255,.4);
            font-size: .7rem;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: .75rem 0;
        }
        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.25);
            padding: .9rem 1.25rem .3rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .55rem 1.25rem;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .83rem;
            font-weight: 450;
            border-radius: 0;
            transition: all .15s;
            margin: 1px 0;
            position: relative;
        }
        .nav-item-link .nav-icon {
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            font-size: .9rem;
            flex-shrink: 0;
            transition: all .15s;
        }
        .nav-item-link:hover {
            color: #fff;
            background: rgba(255,255,255,.06);
        }
        .nav-item-link:hover .nav-icon {
            background: rgba(255,255,255,.1);
        }
        .nav-item-link.active {
            color: #fff;
            background: rgba(79,70,229,.25);
        }
        .nav-item-link.active .nav-icon {
            background: var(--accent);
        }
        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0;
            width: 3px; height: 100%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: .75rem 1rem;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .user-mini {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            border-radius: 10px;
            background: rgba(255,255,255,.05);
        }
        .user-mini .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .8rem;
            flex-shrink: 0;
        }
        .user-mini .user-name {
            color: #fff;
            font-size: .8rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }
        .user-mini .user-role {
            color: rgba(255,255,255,.4);
            font-size: .68rem;
        }

        /* ════════ MAIN AREA ════════ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar .page-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }
        .topbar .breadcrumb {
            font-size: .75rem;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all .15s;
        }
        .topbar-btn:hover { background: #f1f5f9; color: #0f172a; }

        .user-dropdown .btn {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: .3rem .75rem .3rem .4rem;
            font-size: .83rem;
            color: #374151;
            font-weight: 500;
        }
        .user-dropdown .avatar {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .75rem;
        }

        /* ── Content ── */
        .page-content {
            flex: 1;
            padding: 1.5rem;
        }

        /* ════════ CARDS ════════ */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 14px 14px 0 0 !important;
            padding: .9rem 1.25rem;
        }

        /* ════════ STAT CARDS ════════ */
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: #0f172a; line-height: 1; }
        .stat-label { font-size: .78rem; color: #64748b; margin-top: .2rem; }

        /* ════════ BADGES ════════ */
        .role-admin    { background: #ede9fe; color: #7c3aed; }
        .role-trainer  { background: #dcfce7; color: #15803d; }
        .role-karyawan { background: #fef9c3; color: #a16207; }

        .status-draft      { background: #f1f5f9; color: #64748b; }
        .status-published  { background: #dbeafe; color: #1d4ed8; }
        .status-ongoing    { background: #dcfce7; color: #15803d; }
        .status-selesai    { background: #f3f4f6; color: #374151; }
        .status-dibatalkan { background: #fee2e2; color: #dc2626; }

        /* ════════ TABLE ════════ */
        .table { font-size: .85rem; }
        .table thead th {
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ════════ ALERTS ════════ */
        .alert { border-radius: 10px; border: none; font-size: .875rem; }
        .alert-success { background: #f0fdf4; color: #15803d; }
        .alert-danger  { background: #fef2f2; color: #dc2626; }

        /* ════════ MOBILE ════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 199;
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Overlay mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ════════ SIDEBAR ════════ --}}
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="logo">
            <i class="bi bi-mortarboard-fill text-white" style="font-size:.9rem"></i>
        </div>
        <div>
            <div class="app-name">Silatih</div>
            <div class="app-sub">Sistem Pelatihan Karyawan</div>
        </div>
    </a>

    {{-- Menu --}}
    <nav class="sidebar-menu">

        <div class="nav-section-label">Utama</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
            Dashboard
        </a>

        {{-- Admin & Trainer --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
        <div class="nav-section-label">Pelatihan</div>
        <a href="{{ route('pelatihan.index') }}"
           class="nav-item-link {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span>
            Daftar Pelatihan
        </a>
        <a href="{{ route('pendaftaran.index') }}"
           class="nav-item-link {{ request()->routeIs('pendaftaran.index') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-person-check"></i></span>
            Pendaftaran
            @if($pendingCount > 0)
                <span class="badge ms-auto" style="background:#ef4444;font-size:.65rem;border-radius:20px">{{ $pendingCount }}</span>
            @endif
        </a>
        @endif

        {{-- Karyawan --}}
        @if(auth()->user()->isKaryawan())
        <div class="nav-section-label">Pelatihan Saya</div>
        <a href="{{ route('pelatihan.index') }}"
           class="nav-item-link {{ request()->routeIs('pelatihan.index') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-search"></i></span>
            Cari Pelatihan
        </a>
        <a href="{{ route('sertifikat.milik') }}"
           class="nav-item-link {{ request()->routeIs('sertifikat.milik') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-award"></i></span>
            Sertifikat Saya
        </a>
        @endif

        {{-- Admin only --}}
        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Master Data</div>
        <a href="{{ route('users.index') }}"
           class="nav-item-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-people"></i></span>
            Manajemen User
        </a>
        <a href="{{ route('kategori.index') }}"
           class="nav-item-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-tags"></i></span>
            Kategori Pelatihan
        </a>
        @endif

    </nav>

    {{-- User info footer --}}
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="overflow-hidden">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="btn p-0 border-0" style="color:rgba(255,255,255,.35)"
                        title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- ════════ MAIN ════════ --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            {{-- Hamburger (mobile) --}}
            <button class="topbar-btn d-lg-none border-0" onclick="openSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <div class="page-title">@yield('title', 'Dashboard')</div>
            </div>
        </div>

        <div class="topbar-right">
            {{-- User dropdown --}}
            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <span class="badge ms-1 {{ 'role-'.auth()->user()->role }}" style="font-size:.65rem;border-radius:20px">
                        {{ auth()->user()->role }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:12px;border:1px solid #e2e8f0;font-size:.85rem">
                    <li class="px-3 pt-2 pb-1">
                        <div class="fw-semibold text-dark">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }
</script>
@stack('scripts')
</body>
</html>
