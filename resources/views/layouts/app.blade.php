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

        /* SIDEBAR DESKTOP */

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

        .socia-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 9px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .socia-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #D8F0E1;
            color: var(--sidebar);
            font-size: 12px;
            font-weight: 800;
        }

        .socia-user-name {
            overflow: hidden;
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .socia-user-role {
            margin-top: 4px;
            color: #B8D2C3;
            font-size: 12px;
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

        /* TOMBOL TUTUP: HANYA UNTUK MOBILE */

        .socia-mobile-close {
            display: none;
        }

        /* OVERLAY MOBILE */

        .socia-overlay {
            display: none;
        }

        /* KONTEN UTAMA */

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

        /* TOMBOL HAMBURGER */

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
        .socia-mobile-close:focus-visible,
        .socia-overlay:focus-visible {
            outline: 3px solid #6BC58D;
            outline-offset: 3px;
        }

        /* RESPONSIVE TABLET DAN HP */

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
                transition:
                    transform 0.28s ease,
                    visibility 0.28s ease;
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

            .socia-nav {
                display: flex;
                flex-direction: column;
                gap: 6px;
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
                transition:
                    opacity 0.28s ease,
                    visibility 0.28s ease;
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
    @endphp

    <div class="socia-layout">

        <!-- OVERLAY MOBILE -->
        <div
            class="socia-overlay"
            id="sociaOverlay"
            aria-hidden="true"></div>

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
                    @if(request()->routeIs('dashboard')) aria-current="page" @endif
                    >
                    <span class="socia-nav-icon">
                        <i class="bi bi-grid-1x2"></i>
                    </span>
                    Dashboard
                </a>

                <a
                    href="{{ route('tickets.create') }}"
                    class="socia-nav-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}"
                    @if(request()->routeIs('tickets.*')) aria-current="page" @endif
                    >
                    <span class="socia-nav-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    Analyze Ticket
                </a>

                <a href="{{ route('tickets.index') }}"
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
                    @if(request()->routeIs('admin.users.*')) aria-current="page" @endif
                    >
                    <span class="socia-nav-icon">
                        <i class="bi bi-people"></i>
                    </span>
                    User Management
                </a>
                
                <a href="{{ route('knowledge-base.index') }}"
                    class="socia-nav-link {{ request()->routeIs('knowledge-base.*') ? 'active' : '' }}">
                    <span class="socia-nav-icon">
                        <i class="bi bi-journal-text"></i>
                    </span>
                    Knowledge Base
                </a>

                @endif

            </nav>

            <div class="socia-sidebar-bottom">

                <div class="socia-user-card">
                    <div class="socia-avatar">{{ $initials }}</div>

                    <div style="min-width: 0;">
                        <div class="socia-user-name">{{ $currentName }}</div>
                        <div class="socia-user-role">{{ $currentRole }}</div>
                    </div>
                </div>

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

            mobileQuery.addEventListener('change', () => {
                closeMenu();
            });
        })();
    </script>
</body>

</html>