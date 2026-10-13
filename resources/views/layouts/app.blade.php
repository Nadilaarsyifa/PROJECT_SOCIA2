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
            --hover: #F4F7F5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .socia-layout {
            min-height: 100vh;
        }

        .socia-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            width: 258px;
            padding: 26px 18px 20px;
            background: var(--sidebar);
            color: #FFFFFF;
            overflow-y: auto;
        }

        .socia-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 30px;
            color: #FFFFFF;
            text-decoration: none;
        }

        .socia-brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            background: rgba(255, 255, 255, 0.13);
            font-size: 22px;
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

        .socia-main {
            min-height: 100vh;
            margin-left: 258px;
        }

        .socia-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 76px;
            padding: 18px 32px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.9);
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
            padding: 30px 32px;
        }

        .socia-content .bg-white {
            border-radius: 13px;
        }

        @media (max-width: 800px) {
            .socia-sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
                padding: 16px;
            }

            .socia-brand {
                padding-bottom: 18px;
            }

            .socia-nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .socia-sidebar-bottom {
                margin-top: 18px;
                padding-top: 12px;
            }

            .socia-main {
                margin-left: 0;
            }

            .socia-topbar {
                padding: 18px 20px;
            }

            .socia-content {
                padding: 20px;
            }
        }

        @media (max-width: 420px) {
            .socia-nav {
                grid-template-columns: 1fr;
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
        <aside class="socia-sidebar">

            <a href="{{ route('dashboard') }}" class="socia-brand">
                <div class="socia-brand-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <div class="socia-brand-name">PGE-SOCIA</div>
                    <div class="socia-brand-subtitle">SOC INTELLIGENCE</div>
                </div>
            </a>

            <div class="socia-nav-label">Menu Utama</div>

            <nav class="socia-nav">

                <a href="{{ route('dashboard') }}"
                    class="socia-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="socia-nav-icon">
                        <i class="bi bi-grid-1x2"></i>
                    </span>
                    Dashboard
                </a>

                <a href="{{ route('tickets.create') }}"
                    class="socia-nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                    <span class="socia-nav-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    Analyze Ticket
                </a>

                <span class="socia-nav-link disabled"
                    title="Menu akan diaktifkan saat fitur dibuat"
                    aria-disabled="true">
                    <span class="socia-nav-icon">
                        <i class="bi bi-ticket-detailed"></i>
                    </span>
                    All Tickets
                </span>

                @if ($currentUser && $currentUser->hasRole('Administrator'))

                    <a href="{{ route('admin.users.index') }}"
                        class="socia-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="socia-nav-icon">
                            <i class="bi bi-people"></i>
                        </span>
                        User Management
                    </a>

                    <span class="socia-nav-link disabled"
                        title="Menu akan diaktifkan saat fitur dibuat"
                        aria-disabled="true">
                        <span class="socia-nav-icon">
                            <i class="bi bi-journal-text"></i>
                        </span>
                        Knowledge Base / SOP
                    </span>

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

        <div class="socia-main">

            <header class="socia-topbar">
                <div>
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
</body>

</html>