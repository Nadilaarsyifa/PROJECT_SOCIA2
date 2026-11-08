<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PGE-SOCIA') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #F0F3F1;
            --ink: #14261D;
            --muted: #5E6F66;
            --card: #FFFFFF;
            --line: #E3E9E5;
            --sidebar: #0F3B2C;
            --accent: #17824A;
            --accent-dark: #10683B;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        body.sidebar-open {
            overflow: hidden;
        }

        .socia-layout {
            min-height: 100vh;
        }

        /* SIDEBAR */

        .socia-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            width: 258px;
            padding: 26px 18px 20px;
            background: var(--sidebar);
            color: #FFFFFF;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .socia-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 30px;
            color: #FFFFFF;
            text-decoration: none;
        }

        .socia-brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            padding: 3px;
            border-radius: 10px;
            background: #FFFFFF;
            overflow: hidden;
        }

        .socia-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .socia-brand-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        .socia-brand-subtitle {
            margin-top: 3px;
            color: #B8D2C3;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .socia-nav-label {
            margin: 4px 10px 12px;
            color: #A5C3B2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .socia-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .socia-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 45px;
            padding: 11px 13px;
            border-radius: 9px;
            color: #D7E6DD;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .socia-nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
        }

        .socia-nav-link.active {
            background: var(--accent);
            color: #FFFFFF;
        }

        .socia-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 22px;
            font-size: 19px;
        }

        .socia-nav-link.disabled {
            color: #A5BBAE;
            opacity: 0.65;
            cursor: not-allowed;
        }

        .socia-nav-link.disabled:hover {
            background: transparent;
        }

        .socia-sidebar-bottom {
            margin-top: auto;
            padding-top: 25px;
        }

        /* PROFIL SIDEBAR */

        .socia-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 14px 9px;
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            background: transparent;
            color: inherit;
            text-align: left;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s;
        }

        .socia-user-card:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .socia-user-card:focus-visible {
            outline: 2px solid #6BC58D;
            outline-offset: 3px;
        }

        .socia-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #D8F0E1;
            color: var(--sidebar);
            font-size: 13px;
            font-weight: 800;
        }

        .socia-user-name {
            overflow: hidden;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .socia-user-role {
            margin-top: 4px;
            color: #B8D2C3;
            font-size: 13px;
        }

        .socia-logout {
            width: 100%;
            margin-top: 8px;
            padding: 11px 13px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9px;
            background: transparent;
            color: #E3EEE7;
            text-align: left;
            font-family: inherit;
            font-size: 13px;
            cursor: pointer;
        }

        .socia-logout:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .socia-logout i {
            font-size: 15px;
        }

        /* MOBILE CLOSE BUTTON */

        .socia-mobile-close {
            display: none;
        }

        .socia-overlay {
            display: none;
        }

        /* MAIN CONTENT */

        .socia-main {
            min-height: 100vh;
            min-width: 0;
            margin-left: 258px;
        }

        .socia-topbar {
            display: flex;
            align-items: center;
            gap: 16px;
            min-height: 76px;
            padding: 18px 32px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.96);
        }

        .socia-topbar-heading {
            flex: 1;
            min-width: 0;
        }

        .socia-page-heading {
            color: var(--ink);
            font-size: 18px;
            font-weight: 800;
        }

        .socia-page-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        .socia-content {
            min-width: 0;
            padding: 30px 32px;
        }

        .socia-content .bg-white {
            border-radius: 13px;
        }

        /* HAMBURGER */

        .socia-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #FFFFFF;
            color: var(--sidebar);
            font-size: 23px;
            cursor: pointer;
        }

        .socia-menu-toggle:hover {
            background: #F0F7F2;
        }

        .socia-menu-toggle:focus-visible,
        .socia-mobile-close:focus-visible {
            outline: 3px solid #6BC58D;
            outline-offset: 3px;
        }

        /* MODAL PROFIL */

        .socia-profile-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(7, 25, 17, 0.6);
        }

        .socia-profile-modal.is-open {
            display: flex;
        }

        .socia-profile-dialog {
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            max-height: 90dvh;
            overflow-y: auto;
            border-radius: 16px;
            background: #FFFFFF;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .socia-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 28px;
            border-bottom: 1px solid var(--line);
        }

        .socia-profile-title {
            font-size: 21px;
            font-weight: 800;
            color: var(--sidebar);
        }

        .socia-profile-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 9px;
            background: #F0F3F1;
            color: var(--ink);
            font-size: 16px;
            cursor: pointer;
        }

        .socia-profile-close:hover {
            background: #E3E9E5;
        }

        .socia-profile-body {
            padding: 28px;
        }

        .socia-profile-identity {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            padding: 18px;
            border-radius: 12px;
            background: #F0F7F2;
        }

        .socia-profile-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #D8F0E1;
            color: var(--sidebar);
            font-size: 18px;
            font-weight: 800;
        }

        .socia-profile-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--ink);
        }

        .socia-profile-role {
            margin-top: 4px;
            font-size: 14px;
            font-weight: 500;
            color: var(--muted);
        }

        .socia-profile-section-title {
            margin-bottom: 16px;
            font-size: 16px;
            font-weight: 800;
            color: var(--sidebar);
        }

        .socia-profile-field {
            margin-bottom: 18px;
        }

        .socia-profile-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        .socia-profile-field input {
            width: 100%;
            min-height: 46px;
            padding: 11px 14px;
            border: 1px solid #D5DFD8;
            border-radius: 10px;
            outline: none;
            font-family: inherit;
            font-size: 15px;
            color: var(--ink);
        }

        .socia-profile-field input[readonly] {
            background: #F6F8F7;
            color: #3F5046;
        }

        .socia-profile-field input::placeholder {
            color: #8A968E;
        }

        .socia-profile-field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(23, 130, 74, 0.12);
        }

        .socia-profile-submit {
            width: 100%;
            min-height: 48px;
            margin-top: 6px;
            padding: 12px;
            border: 0;
            border-radius: 10px;
            background: var(--accent);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .socia-profile-submit:hover {
            background: var(--accent-dark);
        }

        .socia-profile-alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .socia-profile-alert.success {
            background: #E8F6ED;
            color: #146C37;
        }

        .socia-profile-alert.error {
            background: #FCEBEC;
            color: #A52834;
        }

        /* RESPONSIVE */

        @media (max-width: 800px) {
            .socia-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                width: min(290px, 85vw);
                height: 100vh;
                height: 100dvh;
                padding: 22px 16px;
                padding-top: max(22px, env(safe-area-inset-top));
                padding-bottom: max(20px, env(safe-area-inset-bottom));
                overflow-y: auto;
                transform: translateX(-105%);
                visibility: hidden;
                pointer-events: none;
                transition: transform 0.28s ease, visibility 0.28s ease;
                box-shadow: 12px 0 35px rgba(0, 0, 0, 0.12);
            }

            .socia-sidebar.is-open {
                transform: translateX(0);
                visibility: visible;
                pointer-events: auto;
            }

            .socia-brand {
                padding: 0 5px 26px;
            }

            .socia-sidebar-bottom {
                margin-top: auto;
                padding-top: 24px;
            }

            .socia-main {
                width: 100%;
                margin-left: 0;
            }

            .socia-topbar {
                position: sticky;
                top: 0;
                z-index: 50;
                min-height: 68px;
                gap: 12px;
                padding: 12px 16px;
                padding-top: max(12px, env(safe-area-inset-top));
                background: rgba(255, 255, 255, 0.97);
                backdrop-filter: blur(12px);
            }

            .socia-menu-toggle {
                display: inline-flex;
            }

            .socia-page-heading {
                font-size: 16px;
            }

            .socia-page-subtitle {
                font-size: 10px;
                line-height: 1.5;
            }

            .socia-content {
                padding: 18px 14px;
            }

            .socia-overlay {
                position: fixed;
                inset: 0;
                z-index: 999;
                display: block;
                background: rgba(7, 25, 17, 0.56);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity 0.28s ease, visibility 0.28s ease;
            }

            .socia-overlay.is-visible {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .socia-mobile-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                width: 34px;
                height: 34px;
                margin-left: auto;
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 8px;
                background: transparent;
                color: #FFFFFF;
                font-size: 19px;
                cursor: pointer;
            }
        }

        @media (max-width: 480px) {
            .socia-profile-header,
            .socia-profile-body {
                padding: 20px;
            }

            .socia-profile-title {
                font-size: 19px;
            }
        }

        @media (max-width: 420px) {
            .socia-content {
                padding: 14px 10px;
            }

            .socia-brand-name {
                font-size: 20px;
            }

            .socia-topbar {
                gap: 10px;
                padding-right: 12px;
                padding-left: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .socia-sidebar,
            .socia-overlay {
                transition: none;
            }
        }
    </style>
</head>

<body>
    @php
        $currentUser = auth()->user();

        $currentRole = $currentUser?->getRoleNames()->first()
            ?? $currentUser?->role
            ?? 'Pengguna';

        $currentName = $currentUser?->nama
            ?? $currentUser?->username
            ?? 'Pengguna';

        $initials = strtoupper(substr($currentName, 0, 2));

        $shouldOpenProfileModal =
            $errors->has('current_password')
            || $errors->has('password')
            || session('password-status');
    @endphp

    <div class="socia-layout">

        <!-- OVERLAY MOBILE -->
        <div
            class="socia-overlay"
            id="sociaOverlay"
            aria-hidden="true">
        </div>

        <!-- SIDEBAR -->
        <aside
            class="socia-sidebar"
            id="sociaSidebar"
            aria-label="Menu navigasi utama">

            <div style="display: flex; align-items: center;">
                <a href="{{ route('dashboard') }}" class="socia-brand">
                    <div class="socia-brand-logo">
                        <img
                            src="{{ asset('images/Logo PGE.jpeg') }}"
                            alt="Logo PEMA Global Energi">
                    </div>

                    <div>
                        <div class="socia-brand-name">PGE-SOCIA</div>
                        <div class="socia-brand-subtitle">SOC INTELLIGENCE</div>
                    </div>
                </a>

                <button
                    type="button"
                    class="socia-mobile-close"
                    id="sociaCloseMenu"
                    aria-label="Tutup menu"
                    aria-controls="sociaSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="socia-nav-label">Menu Utama</div>

            <nav class="socia-nav">

                <a
                    href="{{ route('dashboard') }}"
                    class="socia-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                    <span class="socia-nav-icon">
                        <i class="bi bi-grid-1x2"></i>
                    </span>
                    Dashboard
                </a>

                <a
                    href="{{ route('tickets.create') }}"
                    class="socia-nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}"
                    @if(request()->routeIs('tickets.*')) aria-current="page" @endif>
                    <span class="socia-nav-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    Analyze Ticket
                </a>

                <a
                    href="{{ route('tickets.index') }}"
                    class="socia-nav-link {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                    <span class="socia-nav-icon">
                        <i class="bi bi-ticket-detailed"></i>
                    </span>
                    All Tickets
                </a>

                @if ($currentUser && $currentUser->hasRole('Administrator'))

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="socia-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        @if(request()->routeIs('admin.users.*')) aria-current="page" @endif>
                        <span class="socia-nav-icon">
                            <i class="bi bi-people"></i>
                        </span>
                        User Management
                    </a>

                    <a
                        href="{{ route('knowledge-base.index') }}"
                        class="socia-nav-link {{ request()->routeIs('knowledge-base.*') ? 'active' : '' }}">
                        <span class="socia-nav-icon">
                            <i class="bi bi-journal-text"></i>
                        </span>
                        Knowledge Base
                    </a>

                @endif

            </nav>

            <div class="socia-sidebar-bottom">

                <!-- TOMBOL PROFIL -->
                <button
                    type="button"
                    class="socia-user-card"
                    id="openProfileModal"
                    aria-label="Buka profil pengguna">

                    <div class="socia-avatar">{{ $initials }}</div>

                    <div style="min-width: 0;">
                        <div class="socia-user-name">{{ $currentName }}</div>
                        <div class="socia-user-role">{{ $currentRole }}</div>
                    </div>

                    <i class="bi bi-chevron-right ms-auto"></i>
                </button>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="socia-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        &nbsp; Logout
                    </button>
                </form>

            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <div class="socia-main">

            <header class="socia-topbar">

                <button
                    type="button"
                    class="socia-menu-toggle"
                    id="sociaOpenMenu"
                    aria-label="Buka menu navigasi"
                    aria-controls="sociaSidebar"
                    aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>

                <div class="socia-topbar-heading">
                    @isset($header)
                        {{ $header }}
                    @else
                        <div class="socia-page-heading">PGE-SOCIA</div>
                        <div class="socia-page-subtitle">
                            Security Operations Center Intelligence Assistant
                        </div>
                    @endisset
                </div>

            </header>

            <main class="socia-content">
                {{ $slot }}
            </main>

        </div>
    </div>

    <!-- MODAL PROFIL PENGGUNA -->
    <div
        class="socia-profile-modal"
        id="profileModal"
        data-auto-open="{{ $shouldOpenProfileModal ? 'true' : 'false' }}"
        aria-hidden="true">

        <div
            class="socia-profile-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="profileModalTitle">

            <div class="socia-profile-header">
                <div class="socia-profile-title" id="profileModalTitle">
                    Profil Pengguna
                </div>

                <button
                    type="button"
                    class="socia-profile-close"
                    id="closeProfileModal"
                    aria-label="Tutup modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="socia-profile-body">

                <!-- INFORMASI PENGGUNA -->
                <div class="socia-profile-identity">
                    <div class="socia-profile-avatar">
                        {{ $initials }}
                    </div>

                    <div>
                        <div class="socia-profile-name">
                            {{ $currentName }}
                        </div>

                        <div class="socia-profile-role">
                            {{ $currentRole }}
                        </div>
                    </div>
                </div>

                <div class="socia-profile-section-title">
                    Informasi Akun
                </div>

                <div class="socia-profile-field">
                    <label for="profile_name">Nama Pengguna</label>
                    <input
                        type="text"
                        id="profile_name"
                        value="{{ $currentUser?->nama ?? '-' }}"
                        readonly>
                </div>

                <div class="socia-profile-field">
                    <label for="profile_username">Username</label>
                    <input
                        type="text"
                        id="profile_username"
                        value="{{ $currentUser?->username ?? '-' }}"
                        readonly>
                </div>

                <div class="socia-profile-field">
                    <label for="profile_role">Role</label>
                    <input
                        type="text"
                        id="profile_role"
                        value="{{ $currentRole }}"
                        readonly>
                </div>

                <hr style="border-color: var(--line); margin: 24px 0;">

                <!-- FORM GANTI PASSWORD -->
                <div class="socia-profile-section-title">
                    Ganti Password
                </div>

                @if (session('password-status'))
                    <div class="socia-profile-alert success">
                        <i class="bi bi-check-circle"></i>
                        {{ session('password-status') }}
                    </div>
                @endif

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="socia-profile-alert error">
                        <strong>Password gagal diperbarui:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->get('current_password') as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                            @foreach ($errors->get('password') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="socia-profile-field">
                        <label for="current_password">
                            Password Saat Ini
                        </label>

                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            placeholder="Masukkan password saat ini"
                            required>
                    </div>

                    <div class="socia-profile-field">
                        <label for="password">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            required>
                    </div>

                    <div class="socia-profile-field">
                        <label for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            required>
                    </div>

                    <button type="submit" class="socia-profile-submit">
                        Simpan Password Baru
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- SCRIPT DRAWER MOBILE -->
    <script>
        (() => {
            const sidebar = document.getElementById('sociaSidebar');
            const overlay = document.getElementById('sociaOverlay');
            const openButton = document.getElementById('sociaOpenMenu');
            const closeButton = document.getElementById('sociaCloseMenu');

            if (!sidebar || !overlay || !openButton || !closeButton) {
                return;
            }

            const mobileQuery = window.matchMedia('(max-width: 800px)');

            function openMenu() {
                if (!mobileQuery.matches) return;

                sidebar.classList.add('is-open');
                overlay.classList.add('is-visible');
                document.body.classList.add('sidebar-open');

                openButton.setAttribute('aria-expanded', 'true');
                overlay.setAttribute('aria-hidden', 'false');
            }

            function closeMenu() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                document.body.classList.remove('sidebar-open');

                openButton.setAttribute('aria-expanded', 'false');
                overlay.setAttribute('aria-hidden', 'true');
            }

            openButton.addEventListener('click', openMenu);
            closeButton.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });

            sidebar.querySelectorAll('a.socia-nav-link, a.socia-brand')
                .forEach((link) => {
                    link.addEventListener('click', () => {
                        if (mobileQuery.matches) {
                            closeMenu();
                        }
                    });
                });

            mobileQuery.addEventListener('change', closeMenu);
        })();
    </script>

    <!-- SCRIPT MODAL PROFIL -->
    <script>
        (() => {
            const modal = document.getElementById('profileModal');
            const openButton = document.getElementById('openProfileModal');
            const closeButton = document.getElementById('closeProfileModal');
            const sidebar = document.getElementById('sociaSidebar');
            const overlay = document.getElementById('sociaOverlay');
            const menuButton = document.getElementById('sociaOpenMenu');

            if (!modal || !openButton || !closeButton) return;

            function openModal() {
                // Tutup drawer mobile jika sedang terbuka
                if (sidebar && overlay) {
                    sidebar.classList.remove('is-open');
                    overlay.classList.remove('is-visible');
                    document.body.classList.remove('sidebar-open');

                    if (menuButton) {
                        menuButton.setAttribute('aria-expanded', 'false');
                    }

                    overlay.setAttribute('aria-hidden', 'true');
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';

                const passwordInput = document.getElementById('current_password');

                if (passwordInput) {
                    passwordInput.focus();
                }
            }

            function closeModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';

                openButton.focus();
            }

            openButton.addEventListener('click', openModal);
            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    modal.classList.contains('is-open')
                ) {
                    closeModal();
                }
            });

            // Buka kembali modal setelah validasi gagal atau password berhasil diperbarui
            if (modal.dataset.autoOpen === 'true') {
                openModal();
            }
        })();
    </script>
</body>

</html>